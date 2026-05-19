<?php

namespace App\Models;

use App\Enums\PersonType;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function person(): HasOne
    {
        return $this->hasOne(Person::class);
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->person?->type === PersonType::SuperAdmin->value;
    }

    public function isAdmin(): bool
    {
        return $this->person?->type === PersonType::Admin->value;
    }

    public function isEmployee(): bool
    {
        return $this->person?->type === PersonType::Employee->value;
    }

    public function isCustomer(): bool
    {
        return $this->person?->type === PersonType::Customer->value;
    }

    public function isDriver(): bool
    {
        return $this->person?->type === PersonType::Driver->value;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if (app()->environment('local')) {
            return true;
        }

        return match ($panel->getId()) {
            'admin' => $this->isSuperAdmin() || $this->isAdmin(),
            'employee' => $this->isEmployee(),
            'customer' => $this->isCustomer(),
            default => false,
        };
    }
}
