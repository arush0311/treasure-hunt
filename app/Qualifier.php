<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Qualifier extends Model
{
    function student()
    {
    	return $this->belongsTo('App\Student');
    }

    function round()
    {
    	return $this->belongsTo('App\Round');
    }
}
