<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    function student()
    {
    	return $this->belongsTo('App\Student');
    }

    function event()
    {
    	return $this->belongsTo('App\Event');
    }
}
