<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    function password_recovery()
    {
    	return $this->hasOne('App\PasswordRecovery');
    }

    function events()
    {
    	return $this->hasMany('App\Event');
    }

    function registrations()
    {
    	return $this->hasMany('App\Registration');
    }

    function qualifiers()
    {
    	return $this->hasMany('App\Qualifier');
    }
}
