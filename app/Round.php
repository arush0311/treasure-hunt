<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    function event()
    {
    	return $this->belongsTo('App\Event');
    }

    function questions()
    {
    	return $this->hasMany('App\Question');
    }

    function qualifiers()
    {
    	return $this->hasMany('App\Qualifier');
    }
}
