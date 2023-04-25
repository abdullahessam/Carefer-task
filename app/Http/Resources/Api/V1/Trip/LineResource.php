<?php

namespace App\Http\Resources\Api\V1\Trip;

use App\Models\Line;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Line
 */
class LineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'start_station' => new StationResource($this->start_station),
            'end_station' => new StationResource($this->end_station),
            'bus' => new BusResource($this->bus),
            'distance' => $this->distance,
            'price'=>$this->price,
            'date'=>now()->toDateString()

        ];
    }
}
