<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**Poo
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function get_join_date() {
        return Carbon::createFromFormat('Y-m-d',$this->join_date,"Asia/Rangoon")->format('d-m-Y');
    }

    public function get_birthday() {
        return Carbon::createFromFormat('Y-m-d',$this->birthday,"Asia/Rangoon")->format('d-m-Y');
    }

    function position() {
        return $this->belongsTo('App\Position','position_id','id');
    }

    function location() {
        return $this->belongsTo('App\Position','location_id','id');
    }
}
