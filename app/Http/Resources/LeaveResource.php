<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeaveResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
         return [
            'name'=>$this->user->name,
            'type'=>$this->type,
            'from'=>$this->from,
            'to'=>$this->to,
            'no_of_day'=>$this->no_of_day,
            'request'=>$this->user->created_at,
            'status'=>$this->status,
            'remark'=>$this->remark,

         ];
    }
}
