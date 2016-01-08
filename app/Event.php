<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    function student()
    {
    	return $this->belongsTo('App\Student');
    }

    function rounds()
    {
    	return $this->hasMany('App\Round');
    }

    function registrations()
    {
    	return $this->hasMany('App\Registration');
    }
}
