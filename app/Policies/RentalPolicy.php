<?php

namespace App\Policies;

use App\Enums\RentalStatus;
use App\Models\Rental;
use App\Models\User;

class RentalPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin() || $user->isEmployee() || $user->isCustomer();
    }

    public function view(User $user, Rental $rental): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin() || $user->isEmployee()) {
            return true;
        }

        return $user->isCustomer() && $rental->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin() || $user->isEmployee() || $user->isCustomer();
    }

    public function update(User $user, Rental $rental): bool
    {
        if ($user->isCustomer()) {
            return $rental->user_id === $user->id && $rental->status === RentalStatus::Pending->value;
        }

        return $user->isSuperAdmin() || $user->isAdmin() || $user->isEmployee();
    }

    public function delete(User $user, Rental $rental): bool
    {
        return $user->isSuperAdmin();
    }

    public function restore(User $user, Rental $rental): bool
    {
        return $user->isSuperAdmin();
    }

    public function forceDelete(User $user, Rental $rental): bool
    {
        return $user->isSuperAdmin();
    }
}
