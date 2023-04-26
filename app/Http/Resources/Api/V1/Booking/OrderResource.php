<?php

namespace App\Http\Resources\Api\V1\Booking;

use App\Http\Resources\Api\V1\Auth\UserResource;
use App\Http\Resources\Api\V1\Trip\LineResource;
use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Order
 */
class OrderResource extends JsonResource
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
            'id'=> $this->id,
            'line'=>new LineResource($this->line),
            'user'=>new UserResource($this->user),
            'seats'=>$this->seats()->pluck('seat_number'),
            'sub_total'=>$this->sub_total,
            'discount'=>$this->discount,
            'total'=>$this->total,
            'status'=>$this->status,
            'created_at'=>$this->created_at->toDateTimeString(),
        ];
    }
}
