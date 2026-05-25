<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'vehicles';

    protected $casts = [
        'year' => 'integer',
        'requires_driver' => 'boolean',
    ];

    protected $fillable = [
        'name',
        'year',
        'transmission',
        'requires_driver',
        'license_plate',
        'description',
        'image',
        'rental_rate_per_day',
    ];

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }
}
