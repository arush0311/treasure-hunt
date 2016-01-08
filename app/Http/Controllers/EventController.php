<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Student;
use App\Event;
use App\Round;
use App\Question;
use App\Registration;
use App\Qualifier;
use Carbon\Carbon;

use Mail;
use Session;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
	public function create_event(Request $request)
	{
		$event_image = null;
		$background_image = null;
		$json['messages'] = [];
		$json['errors'] = [];
		$validator = Validator::make($request->all(),[
			'name' => 'required',
			'no_of_rounds' => 'required|integer|max:3|min:1',
			'category' => 'required|in:Computer Science,Electronics,History,Others',
			'description' => 'required',
			'prizes' => 'required',
			'event_image' => 'required',
			'background_image' => 'required',	
			'rounds' => 'required|array',
		]);

		$validator->after(function($validator) use ($request,&$event_image,&$background_image){
			if(!Session::has('id'))
			{
				$validator->errors()->add('user','Please login to continue');
			}
			if(($event_image = base64_decode($request->event_image)) && ($background_image = base64_decode($request->background_image)))
			{
				if(strlen($event_image)>2097152 || strlen($background_image)>2097152)
				{
					$validator->errors()->add('image','image size exceeded');
				}
			}
			else
			{
				$validator->errors()->add('image','curropted image');
			}
			if(count($request->rounds) != $request->no_of_rounds)
			{
				$validator->errors()->add('round','Enter the correct no of rounds');
			}
			else
			{
				foreach($request->rounds as $round)
				{
					$subvalidator = Validator::make($round,[
						'no_of_questions' => 'required|integer|min:1',
						'date' => 'required|date_format:Y-m-d|after:'.date('Y-m-d'),
						'time' => 'required|date_format:H:i',
						'cutoff' => 'integer',
						'duration' => 'required|integer|min:1|max:3',
						'questions' => 'required|array'
					]);

					$subvalidator->after(function($subvalidator) use ($round){
						foreach($round['questions'] as $question)
						{
							$subsubvalidator = Validator::make($question,[
								'question' => 'required',
								'option1' => 'required',
								'option2' =>  'required',
								'option3' => 'required',
								'option4' => 'required',
								'answer' => 'required|in:"'.$question['option1'].'","'.$question['option2'].'","'.$question['option3'].'","'.$question['option4'].'"',
							]);

							if($subsubvalidator->fails())
							{
								$subvalidator->errors()->merge($subsubvalidator->errors()->toArray());
								break;

							}
						}

					});

					if($subvalidator->fails())
					{
						$validator->errors()->merge($subvalidator->errors()->toArray());
						break;
					}
				}
			}
		});

		if($validator->fails())
		{
			$json['errors'] = $validator->errors()->toArray();
			return response()->json($json);
		}
		else
		{
			$student = Student::where('id',Session::get('id'))->where('validated',true)->first();
			$event = new Event;
			$event->name = $request->name;
			$event->no_of_rounds = $request->no_of_rounds;
			$event->category = $request->category;
			$event->description = $request->description;
			$event->prizes = $request->prizes;
			$event->background_image = "abcd";
			$event->event_image = "abcd";
			$event->save();
			$tempslug = str_slug($event->name,"-");
			if(!Event::where('slug',$tempslug)->first())
			{
				$event->slug = 	$tempslug;
			}
			else
			{
				$event->slug = $tempslug."-".$event->id;
			}
			$event->background_image = "/img/uploaded/background_".$event->id.".jpg";
			$event->event_image = "/img/uploaded/event_".$event->id.".jpg";
			$fh = fopen(public_path("img/uploaded/event_".$event->id.".jpg"),"w");
			fwrite($fh,$event_image);
			fclose($fh);
			$fh = fopen(public_path("img/uploaded/background_".$event->id.".jpg"),"w");
			fwrite($fh,$background_image);
			fclose($fh);
			$student->events()->save($event);

			foreach($request->rounds as $i => $r)
			{
				$round = new Round;
				$round->no = $i;
				$tz = new \DateTimeZone('Asia/Calcutta');
				$round->start_date_time = new \DateTime($r['date']." ".$r['time'].":00",$tz);
				$round->duration = $r['duration'];
				$round->end_date_time = new \DateTime($r['date']." ".$r['time'].":00",$tz);
				$round->end_date_time->add(new \DateInterval('PT'.$r['duration'].'H'));
				$round->cutoff = (isset($r['cutoff']) ? $r['cutoff'] : null);
				$round->no_of_questions = $r['no_of_questions'];
				$round->save();
				$event->rounds()->save($round);
				foreach($r['questions'] as $j => $q)
				{
					$question = new Question;
					$question->no = $j;
					$question->question = $q['question'];
					$question->options = serialize([$q['option1'],$q['option2'],$q['option3'],$q['option4']]);
					$question->answer = $q['answer'];
					$question->save();
					$round->questions()->save($question);
				}
			}
			$json['messages'] = "successfull";
			return response()->json($json);
		}
	}

	public function search_events($search_string = "")
	{
		$json['errors'] = [];
		$json['messages'] = [];
		$data = [];
		$events = Event::where('name','LIKE',"%".$search_string."%")->where('verified',true)->get();
		$i = 0;
		if(Session::has('id'))
		{
			$json['logged_in'] = true;
			$student = Student::where('id',Session::get('id'))->first();
		}
		else
		{
			$json['logged_in'] = false;
		}
		foreach($events as $event)
		{
			$data[$i]['name'] = $event->name;
			$data[$i]['description'] = $event->description;
			$data[$i]['prizes'] = $event->prizes;
			$data[$i]['background_image'] = $event->background_image;
			$data[$i]['event_image'] = $event->event_image;
			$data[$i]['slug'] = $event->slug;
			$data[$i]['rounds'] = [];
			$rounds = $event->rounds;
			foreach($rounds as $round)
			{
				$data[$i]['rounds'][$round->no]['date_time'] = strtotime($round->start_date_time)*1000;
				$data[$i]['rounds'][$round->no]['duration'] = $round->duration;
				$data[$i]['rounds'][$round->no]['cutoff'] = $round->cutoff;
				$data[$i]['rounds'][$round->no]['no_of_questions'] = $round->no_of_questions;

			}
			$i = $i + 1;
		}
		$json['data'] = $data;
		return response()->json($json);
	}

	public function show_event($event_slug)
	{
		$json['errors'] = [];
		$json['messages'] = [];
		$data = [];
		$event = Event::where('slug',$event_slug)->where('verified',true)->first();
		if(!$event)
		{
			$json['errors'][] = "event not found";
			return response()->json($json);
		}
		$data['timestamp'] = time()*1000;
		if(Session::has('id'))
		{
			$json['logged_in'] = true;
			if($registration = Registration::where('student_id',Session::get('id'))->where('event_id',$event->id)->first())
			{
				$data['registered'] = true;
			}
			else
			{
				$data['registered'] = false;
			}
		}
		else
		{
			$json['logged_in'] = false;
		}
		$data['creator_name'] = $event->student->name;
		$data['creator_email'] = $event->student->email;
		$data['name'] = $event->name;
		$data['description'] = $event->description;
		$data['prizes'] = $event->prizes;
		$data['background_image'] = $event->background_image;
		$data['event_image'] = $event->event_image;
		$data['slug'] = $event->slug;
		$data['rounds'] = [];
		$rounds = $event->rounds;
		foreach($rounds as $round)
		{
			$data['rounds'][$round->no]['date_time'] = strtotime($round->start_date_time)*1000;
			$data['rounds'][$round->no]['duration'] = $round->duration;
			$data['rounds'][$round->no]['cutoff'] = $round->cutoff;
			$data['rounds'][$round->no]['no_of_questions'] = $round->no_of_questions;
			if(Session::has('id'))
			{
				$student = $round->qualifiers->where('student_id',Session::get('id'))->first();
				if($student)
				{
					$data['rounds'][$round->no]['qualified'] = true;
				}
				else
				{
					$data['rounds'][$round->no]['qualified'] = false;
				}
			}
		}

		$json['data'] = $data;
		$json['messages'][] = "Successfull";
		return response()->json($json);
	}

	public function register($event_slug)
	{
		$json['errors'] = [];
		$json['messages'] = [];

		$event = Event::where('slug',$event_slug)->where('verified',true)->first();
		
		if(Session::has('id'))
		{
			$student = Student::where('id',Session::get('id'))->first();
			if($event)
			{
				if(Registration::where('student_id',Session::get('id'))->where('event_id',$event->id)->first())
				{
					$json['errors'][] = "Already Registered";
				}
				else
				{
					$round = $event->rounds->where('no','1')->first();
					$qualifier = new Qualifier;
					$qualifier->save();
					$round->qualifiers()->save($qualifier);
					$student->qualifiers()->save($qualifier);
					$registration = new Registration;
					$registration->save();
					$student->registrations()->save($registration);
					$event->registrations()->save($registration);
					$json['messages'][] = "successfull";

				}
			}
			else
			{
				$json['errors'][] = "Event dont exist";
			}
		}
		else
		{
			$json['errors'][] = "Please login to continue";
		}

		return response()->json($json);
	}
		
	








}
