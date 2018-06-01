<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;
use App\Supervisor;
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

    public static function paginate($int)
    {
    }

    public function get_join_date() {
        return Carbon::createFromFormat('Y-m-d',$this->join_date,"Asia/Rangoon")->format('d-m-Y');
    }

    public function get_long_time() {
        //return Carbon::createFromFormat('Y-m-d',$this->join_date,"Asia/Rangoon")->format('d-m-Y');   
        
        return Carbon::parse($this->join_date)->diff(Carbon::now())->format('%y years, %m months and %d days');
        
        
    }

    public function has_anniversary() {
      $raw_date = Carbon::parse($this->join_date)->diff(Carbon::now())->format('%y,%m,%d');
      $date_array = explode(',', $raw_date);


      if($date_array[0] > 0 && $date_array[1] == 0 && $date_array[2] == 0)
      {
        return Carbon::now()->toDateString();
      }

      return false;


    }

    public function get_long_time_year_month() {
        //return Carbon::createFromFormat('Y-m-d',$this->join_date,"Asia/Rangoon")->format('d-m-Y');   
        
        return Carbon::parse($this->join_date)->diff(Carbon::now())->format('%y years, %m months');
        
        
    }

    

    public function age() {
      return Carbon::parse($this->birthday)->diff(Carbon::now())->format('%y years');
    }

    public function get_birthday() {
        return Carbon::createFromFormat('Y-m-d',$this->birthday,"Asia/Rangoon")->format('d-m-Y');
    }

    function position() {
        return $this->belongsTo('App\Position','position_id','id');
    }

    function location() {
        return $this->belongsTo('App\Location','location_id','id');
    }

    function supervisor() {
        return $this->hasOne('App\User','id','supervisor_id');
    }

    function is_admin() {

      return ($this->position->level <= config('comquas.position'));
    }

    function staff() {
      return User::where('supervisor_id',$this->id)->get();
    }



}
