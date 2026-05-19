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
        'user_id',
        'total_amount',
        'status',
        'delivery_method',
        'delivery_address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rentalItems(): HasMany
    {
        return $this->hasMany(RentalItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getPaymentStatusAttribute(): string
    {
        if ($this->relationLoaded('payments') && $this->payments->isNotEmpty()) {
            if ($this->payments->contains('status', 'paid')) {
                return 'paid';
            }

            if ($this->payments->contains('status', 'pending')) {
                return 'pending';
            }
        }

        $hasPaid = $this->payments()->where('status', 'paid')->exists();
        if ($hasPaid) {
            return 'paid';
        }

        $hasPending = $this->payments()->where('status', 'pending')->exists();
        if ($hasPending) {
            return 'pending';
        }

        return 'unpaid';
    }
}
