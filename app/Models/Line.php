<?php

namespace App\Models;

use App\Helpers\Traits\LockService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Command\LockableTrait;

/**
 * App\Models\Line.
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
 * @property-read \App\Models\Bus $bus
 * @method static Builder|Line filter()
 * @mixin \Eloquent
 */
class Line extends Model
{
    use HasFactory,LockService;

    protected $fillable = ['start_station_id', 'end_station_id', 'distance', 'price', 'bus_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function start_station()
    {
        return $this->belongsTo(Station::class, 'start_station_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function end_station()
    {
        return $this->belongsTo(Station::class, 'end_station_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    /**
     * filter lines by start and end stations.
     * @param Builder $builder
     * @return void
     */
    public function scopeFilter(Builder $builder)
    {
        $builder
            ->when(request('start_station_id'), function ($builder) {
                $builder->where('start_station_id', request('start_station_id'));
            })->when(request('end_station_id'), function ($builder) {
                $builder->where('end_station_id', request('end_station_id'));
            });
    }
}
