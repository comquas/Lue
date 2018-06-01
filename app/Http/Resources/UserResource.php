<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    { $dt=Carbon::now();
        return [
            'name' => $this->name,
            'email'=>$this->email,
            'position'=>$this->position->title,
            'salary'=>$this->salary,
              'time'=> Carbon::parse($this->join_date)->diff(Carbon::now())->format('%y years, %m months and %d days'),


        ];
    }

    public function time()
    {
        return $this->year(). " " .str_plural('year', $this->year()) . "{$this->month()} months";

    }

     public function year()
    {
        return $this->created_at->diff(Carbon::now()->year);
    }

    protected function month()
    {
        return $this->created_at->diff($dt->month);
    } 
  

}
