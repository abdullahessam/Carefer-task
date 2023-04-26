<?php

namespace App\Http\Resources\Api\V1\Report;

use Illuminate\Http\Resources\Json\JsonResource;

class FrequentTipBookedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'email'=>$this->email,
            'frequentBook'=>$this->most_booked_trip,
        ];
    }
}
