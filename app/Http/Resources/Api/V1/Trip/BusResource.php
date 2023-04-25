<?php

namespace App\Http\Resources\Api\V1\Trip;

use App\Models\Bus;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Bus
 */
class BusResource extends JsonResource
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
        ];
    }
}
