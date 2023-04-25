<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Order.
 *
 * @property int $id
 * @property int $user_id
 * @property int $line_id
 * @property string $sub_total
 * @property string $discount
 * @property string|null $total
 * @property string $date
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Line $line
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderSeat> $seats
 * @property-read int|null $seats_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereLineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @mixin \Eloquent
 */
class Order extends Model
{

    protected $fillable = ['user_id', 'total', 'sub_total', 'discount', 'date', 'line_id', 'status'];

    public function line()
    {
        return $this->belongsTo(Line::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seats()
    {
        return $this->hasMany(OrderSeat::class);
    }

    public function scopeFilter(Builder $builder)
    {
        $builder
            ->where('user_id', auth()->user()->id)
            ->when(request('status'), fn ($query) => $query->where('status', request('status')))
            ->when(request('line_id'), fn ($query) => $query->where('line_id', request('line_id')))
        ->latest();
    }
}
