<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;

class VehiclePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin() || $user->isEmployee() || $user->isCustomer();
    }

    public function view(User $user, Vehicle $vehicle): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin() || $user->isEmployee() || $user->isCustomer();
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    public function update(User $user, Vehicle $vehicle): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    public function delete(User $user, Vehicle $vehicle): bool
    {
        return $user->isSuperAdmin();
    }

    public function restore(User $user, Vehicle $vehicle): bool
    {
        return $user->isSuperAdmin();
    }

    public function forceDelete(User $user, Vehicle $vehicle): bool
    {
        return $user->isSuperAdmin();
    }
}
