<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Leave extends Model
{
    function user() {
    	return $this->hasOne('App\User','id','user_id');
    }

    public function get_from_date() {
        return Carbon::createFromFormat('Y-m-d',$this->from,"Asia/Rangoon")->format('d-m-Y');
    }

    public function get_to_date() {
        return Carbon::createFromFormat('Y-m-d',$this->to,"Asia/Rangoon")->format('d-m-Y');
    }
}
