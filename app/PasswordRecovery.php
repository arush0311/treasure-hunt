<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordRecovery extends Model
{
    function student()
    {
    	$this->belongsTo('App\Student');
    }
}
