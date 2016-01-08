<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    function round()
    {
    	return $this->belongsTo('App\Round');
    }
}
