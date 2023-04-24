<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderSeat
 *
 * @property int $id
 * @property int $order_id
 * @property int $seat_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder|OrderSeat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderSeat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderSeat query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderSeat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderSeat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderSeat whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderSeat whereSeatNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderSeat whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderSeat extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'seat_number'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
