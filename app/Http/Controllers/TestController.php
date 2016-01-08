<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Event;
use App\Student;
use App\Qualifier;
use App\Round;
use App\Question;

use Carbon\Carbon;
use Session;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TestController extends Controller
{

    public function show_test($event_slug,$round_no){
    	$json['errors'] = [];
    	$json['messages'] = [];
    	$json['timestamp'] = time()*1000;

    	if(!Session::has('id')){
    		$json['errors']['login'] = "Please login to Continue";
    		return response()->json($json);
    	}

    	$student = Student::where('id',Session::get('id'))->where('validated',true)->first();
    	$event = Event::where('slug',$event_slug)->where('verified',true)->first();

    	if(!$event)
    	{
    		$json['errors']['event'] = "This event doesnot exist";
    		return response()->json($json);
    	}

    	$round = $event->rounds()->where('no',$round_no)->first();

    	if(!$round)
    	{
    		$json['errors']['round'] = "This round doesnot exist";
    		return response()->json($json);
    	}

        $qualifier = $round->qualifiers()->where('student_id',$student->id)->first();

        if(!$qualifier)
        {
            $json['errors']['round'] = "You are not qualified for this Round";
            return response()->json($json);
        }

    	$now = Carbon::now();
    	if($now->gte(new Carbon($round->start_date_time)) && (new Carbon($round->start_date_time))->addHours($round->duration)->gt($now))
    	{
    		$questions = $round->questions()->orderBy('no','asc')->get();
    		$i = 1;
            $json['data']['name'] = $event->name;
            $json['data']['round_no'] = $round->no;
    		$json['data']['date_time'] = strtotime($round->start_date_time)*1000;
    		$json['data']['duration'] =  $round->duration;
            $json['data']['no_of_questions'] = $round->no_of_questions;
    		foreach($questions as $question)
    		{
    			$options = unserialize($question->options);
    			$json['data']['questions'][$i]['question'] = $question->question;
    			$json['data']['questions'][$i]['option1'] = $options[0];
    			$json['data']['questions'][$i]['option2'] = $options[1];
    			$json['data']['questions'][$i]['option3'] = $options[2];
    			$json['data']['questions'][$i]['option4'] = $options[3];
                $i++;

    		}

            return response()->json($json);
    	}
    	else
    	{
    		$json['errors']['round'] = "You can only enter the round during the given time";
    		return response()->json($json);
    	}
    }

    public function submit_test(Request $request,$event_slug,$round_no){
        $json['errors'] = [];
        $json['messages'] = [];

        if(!Session::has('id')){
            $json['errors']['login'] = "Please login to Continue";
            return response()->json($json);
        }

        $student = Student::where('id',Session::get('id'))->where('validated',true)->first();
        $event = Event::where('slug',$event_slug)->where('verified',true)->first();

        if(!$event)
        {
            $json['errors']['event'] = "This event doesnot exist";
            return response()->json($json);
        }

        $round = $event->rounds()->where('no',$round_no)->first();
        if(!$round)
        {
            $json['errors']['round'] = "This round doesnot exist";
            return response()->json($json);
        }

        $qualifier = $round->qualifiers()->where('student_id',$student->id)->first();
        if(!$qualifier)
        {
            $json['errors']['round'] = "You are not qualified for this Round";
            return response()->json($json);
        }

        $now = Carbon::now();

        if($now->gte(new Carbon($round->start_date_time)) && (new Carbon($round->start_date_time))->addHours($round->duration)->addMinutes(5)->gt($now))
        {
            $score = 0;
            $i = 1;
            $questions = $round->questions()->orderBy('no','asc')->get();

            foreach($questions as $question)
            {
                if(isset($request->answers[$i]) && $request->answers[$i] == $question->answer)
                {
                    $score++;
                }
                $i++;
            }

            $qualifier->score = $score;
            $qualifier->completion_time = $now;
            $qualifier->save();
            $json['message'] = "sucessfull";
            return response()->json($json);
        }
        else
        {
            $json['errors']['round'] = "You can only finish round during the given time";
            return response()->json($json);
        }

    }
}
