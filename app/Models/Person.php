<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'people';

    protected $fillable = [
        'user_id',
        'type',
        'phone',
        'address',
        'id_type',
        'id_file_path',
        'driver_fee_per_day',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
