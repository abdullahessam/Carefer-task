<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bus.
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\BusFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Bus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bus query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Bus extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
}
