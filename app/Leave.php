<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    function user() {
    	return $this->hasOne('App\User','id','user_id');
    }
}
