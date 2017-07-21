<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
	public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function current() {
    	return $this->hasOne('App\User','id','supervisor_id');
    }
}
