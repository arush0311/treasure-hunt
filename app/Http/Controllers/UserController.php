<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Student;
use App\PasswordRecovery;
use Validator;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
	private $ALL_INTERESTS  = [
		'Computer Science',
		'Electronics',
		'History',
		'Others'
	];

    public function enter()
    {
        return view('master');
    }

    public function loginpage()
    {
        if(Session::has('id'))
        {
            return redirect('/');
        }
        else
        {
            return view('loginpage');
        }
    }
    public function register(Request $request)
    {
    	$json = [];
    	$json['errors'] = [];
    	$json['messages'] = [];
    	$validator = Validator::make($request->all(),[
    			'name' => 'required|string',
    			'city' => 'required|string',
    			'email' => 'required|email|unique:students,email,true,validated',
    			'branch' => 'string|required',
    			'password' => 'required|string|min:8',
    			'repeat_password' => 'same:password',
    			'college_name' => 'string|required',
    			'semester' => 'required|min:1|max:8',
    			'interests' => 'required|array'

    		]);
    	$validator->after(function($validator) use ($request) {
    		if(@count($request->interests) < 2)
    		{
    			$validator->errors()->add('interests','Atleast two interests must be entered');
    		}
    		if(@array_intersect($request->interests,$this->ALL_INTERESTS) != $request->interests)
    		{
    			$validator->errors()->add('interests','Interests can only be one of the given inputs');
    		}

    	});

    	if($validator->fails())
    	{
    		
    		$json['errors'] = $validator->messages()->toArray();
    		return response()->json($json);
    	}
    	else
    	{
    		$student = Student::where('email',$request->email)->first();
    		if(!$student)
    		{
    			$student = new Student;
    		}

    		$student->name         =  $request->name;
    		$student->city         =  $request->city;
    		$student->email        =  $request->email;
    		$student->password     =  sha1($request->password);
    		$student->college_name =  $request->college_name;
    		$student->branch       =  $request->branch;
    		$student->semester     =  $request->semester;
    		$student->interests    =  serialize($request->interests);
    		$student->token        =  bin2hex(openssl_random_pseudo_bytes(16));

    		$student->save();

    		$json['messages']= "Registration under process. Please verify your email";

    		Mail::send('mail/register',[
    			'token'=>$student->token,
    			'name'=>$student->name,
    			'email'=>$student->email
    			],function($message) use ($student){
    				$message->to($student->email)->subject('TheTreasureHunt: Verification Mail');
    		});

    		return response()->json($json);
		}
    }

    public function verify($email,$token)
    {
    	$student = Student::where('token',$token)->where('email',$email)->first();

    	if(!$student)
    	{
    		$msg = "Your link has expired. Please register gain";
    	}
    	else{
    		if(time() - strtotime($student->updated_at) > 86400)
    		{
    			$msg = "Your link has expired. Please register gain";
    		}
    		else
    		{
    			$student->token = NULL;
    			$student->validated = true;
    			$student->save();

    			$msg = "You have successfully registered. Please login";
    		}
    	}

    	return redirect('/login')->withError('popup',$msg);
    } 

    public function login(Request $request)
    {
    	$json['errors'] = [];
    	$json['messages'] = [];
    	
    	$validator = Validator::make($request->all(),[
    			'email' => 'required',
    			'password' => 'required'
    		]);

		$student = Student::where('email',$request->email)->where('password',sha1($request->password))->where('validated',true)->first();
		$validator->after(function($validator) use ($student){
			if(!$student)
			{
				$validator->errors()->add('email','The credentials you provided are incorrect');
			}
		});
    	if($validator->fails())
    	{
    		$json['errors']  = $validator->messages()->toArray();
    		return response()->json($json);
    	}
    	else{
    		Session::put('email',$student->email);
    		Session::put('id',$student->id);

    		$json['messages'][] = "logged_in";
    		return response()->json($json);
    	}
    }

    public function logout()
    {
    	$json['errors'] = [];
    	$json['messages'] = [];

    	Session::forget('email');
    	Session::forget('id');
    	$json['messages'][] = 'logged_out';

    	return response()->json($json);

    }

    public function change_password(Request $request)
    {
    	$json['errors'] = [];
    	$json['messages'] = [];

    	if(!Session::has('id'))
    	{
    		$json['errors'][] = "Please log in to continue";
    		return response()->json($json);
    	}
    	else
    	{
    		$validator = Validator::make($request->all(),[
    				'password' => 'required',
    				'new_password' => 'required|min:8|string',
    				'repeat_new_password' => 'required|same:new_password'
    			]);

    		$validator->after(function($validator) use ($request){
    			if(Student::where('id',Session::get('id'))->first()->password != sha1($request->password))
    			{
    				$validator->errors()->add('password','Please enter the correct password');
    			}
    		});

    		if($validator->fails())
    		{
    			$json['errors'] = $validator->messages()->toArray();

    			return response()->json($json);
    		}
    		else{

    			$student = Student::where('id',Session::get('id'))->first();
    			$student->password = sha1($request->new_password);
    			$student->save();

    			$json['messages'][] = "password_changed";

    			return response()->json($json);
    		}
    	}
    }

    public function forgot_password(Request $request,$email=NULL,$token = NULL)
    {
    	$json['errors'] = [];
    	$json['messages'] = [];

    	if($token == NULL)
    	{
            $validator = Validator::make($request->all(),[
                    'email' => 'required|email',
                    'new_password' => 'required|min:8|string',
                    'repeat_new_password' => 'same:new_password'
            ]);

            $student = Student::where('email',$request->email)->where('validated',true)->first();
    		$validator->after(function($validator) use ($student){
    			if(!$student)
    			{
    				$validator->errors()->add('email','You are not registered with us');
    			}
    		});

    		if($validator->fails())
    		{
    			$json['errors'] = $validator->messages()->toArray();

    			return response()->json($json);
    		}
    		else
    		{
    			$password_recovery = $student->password_recovery;
    			if(!$password_recovery)
    			{
    				$password_recovery = new PasswordRecovery;
    			}

    			$password_recovery->token = bin2hex(openssl_random_pseudo_bytes(4));
                $password_recovery->new_password = sha1($request->new_password);
    			$password_recovery->save();
                $student->password_recovery()->save($password_recovery);
    			Mail::send('mail/password_recovery',[
					'token' => $password_recovery->token,
                    'email' => $student->email,
					'name' => $student->name,
				],function($message) use ($student){
					$message->to($student->email);
					$message->subject('TheTreasureHunt: Your Request for Password Reset');
				});
    		}

    		$json['messages'][] = "successfull";

    		return response()->json($json);
    	}
    	else
    	{

            $student = Student::where('email',$email)->where('validated',true)->first();
            if(!$student)
            {
                $json['errors']['email'] = "You are not registered with us";
            }
            else
            {
                @$password_recovery = $student->password_recovery;
    			if($password_recovery && $password_recovery->token == $token && (time() -strtotime($password_recovery->updated_at)) < 86400 )
                {
                    $student->password = $password_recovery->new_password;
                    $student->save();
                    $password_recovery->delete();
                    $json['messages'][] = 'Successfull';
                }
                else
                {
                    $json['errors']['link'] = 'Your link has expired';
                }
            }
            if(count($json['errors']))
            {
                return response()->json($json);
            }
            return redirect('/login') ;
    	}
    }

    public function edit_profile(Request $request)
    {

        $json['errors'] = [];
        $json['messages'] = [];

        if(!Session::has('id'))
        {
            $json['errors'][] = "Please log in to continue";
            return response()->json($json);
        }

        $validator = Validator::make($request->all(),[
            'city' => 'required|string',
            'branch' => 'string|required',
            'college_name' => 'string|required',
            'semester' => 'required|min:1|max:8',
            'interests' => 'required|array'
        ]);

        $validator->after(function($validator) use ($request) {
            if(@count($request->interests) < 2)
            {
                $validator->errors()->add('interests','Atleast two interests must be entered');
            }
            if(@array_intersect($request->interests,$this->ALL_INTERESTS) != $request->interests)
            {
                $validator->errors()->add('interests','Interests can only be one of the given inputs');
            }

        });

        if($validator->fails())
        {
            
            $json['errors'] = $validator->messages()->toArray();
            return response()->json($json);
        }
        else
        {
            $student = Student::where('id',Session::get('id'))->first();

            $student->city         = $request->city;
            $student->college_name = $request->college_name;
            $student->branch       = $request->branch;
            $student->semester     = $request->semester;
            $student->interests    = serialize($request->interests);

            $student->save();

            $json['messages'][] = 'Successfull';
            return response()->json($json);
        }
    }

    public function get_profile()
    {
        $json['errors'] = [];
        $json['messages'] = [];
        $json['data'] = [];
        if(!Session::has('id'))
        {
            $json['errors'][] = "Please log in to continue";
            return response()->json($json);
        }
        else
        {
            $student = Student::where('id', Session::get('id'))->first();

            $json['data']['name']         =  $student->name;
            $json['data']['city']         =  $student->city;
            $json['data']['college_name'] =  $student->college_name;
            $json['data']['branch']       =  $student->branch;
            $json['data']['semester']     =  $student->semester;
            $json['data']['interests']    =  unserialize($student->interests);

            $json['messages'][] = "successfull";
            return response()->json($json);
        }
    }
}
