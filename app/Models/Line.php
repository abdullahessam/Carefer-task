<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Line
 *
 * @property int $id
 * @property int $start_station_id
 * @property int $end_station_id
 * @property int $distance
 * @property string $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Station $end_station
 * @property-read \App\Models\Station $start_station
 * @method static \Database\Factories\LineFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Line newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Line newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Line query()
 * @method static \Illuminate\Database\Eloquent\Builder|Line whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Line whereDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Line whereEndStationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Line whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Line wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Line whereStartStationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Line whereUpdatedAt($value)
 * @property int $bus_id
 * @method static \Illuminate\Database\Eloquent\Builder|Line whereBusId($value)
 * @mixin \Eloquent
 */
class Line extends Model
{
    use HasFactory;

    protected $fillable = ['start_station_id', 'end_station_id', 'distance','price','bus_id'];

    public function start_station()
    {
        return $this->belongsTo(Station::class, 'start_station_id');
    }

    public function end_station()
    {
        return $this->belongsTo(Station::class, 'end_station_id');
    }
}
