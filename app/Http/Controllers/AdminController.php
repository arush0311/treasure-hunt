<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Session;
use App\Event;
use App\Student;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Admin;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        if($request->isMethod('GET'))
        {
            return view('admin/login');
        }

        else if($request->isMethod('POST'))
        {
            $validator = Validator::make($request->all(),[
                'username' => 'required',
                'password' => 'required'
            ]);

            $validator->after(function ($validator) use ($request){
                if(!(Admin::where('username',$request->username)->where('password',$request->password)->first()))
                {
                   $validator->errors()->add('login', 'The credentials you provided are incorrect');
                }
            });

            if($validator->fails())
            {
                return redirect('/admin/login')->withErrors($validator, 'login');
            }

            else
            {
                $admin = Admin::where('username',$request->username)->where('password',$request->password)->first();
                Session::put('admin',true);
                return redirect('/admin/home');
            }

        }
    }


    public function home(Request $request)
    {
        if(!Session::has('admin'))
        {
            return redirect('/admin/login');
        }

        if($request->isMethod('GET'))
        {
            $events = Event::where('verified',false)->get();
            return view('admin/home',[
                'events' => $events
            ]);
        }

        else if($request->isMethod('POST'))
        {
            $this->validate($request,[
                'event_slug' => 'required|exists:events,slug',
                'verify' => 'required'
            ]);
            $event = Event::where('slug',$request->event_slug)->first();
            if($request->verify == "accept")
            {
                $event->verified = true;
                $event->save();
            }
            else if($request->verify = 'reject')
            {
                $event->delete();
            }
            return redirect('/admin/home');
        }
    }

    public function logout()
    {
        if(Session::has('admin'))
        {
            Session::forget('admin');
        }

        return redirect('/admin/login');
    }

    public function students()
    {
        if(!Session::has('admin'))
        {
            return redirect('/admin/login');
        }
        $students = Student::where('validated',true)->get();

        return view('admin/students',[
            'students' => $students
        ]);
    }
}




