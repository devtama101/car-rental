<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rental extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rentals';

    protected $fillable = [
        'booking_reference',
        'user_id',
        'vehicle_id',
        'start_date',
        'end_date',
        'driver_id',
        'driver_fee_per_day',
        'rental_rate_per_day',
        'total_amount',
        'status',
        'delivery_method',
        'delivery_address',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'driver_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
