<?php

namespace App\Policies;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin() || $user->isEmployee() || $user->isCustomer();
    }

    public function view(User $user, Payment $payment): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin() || $user->isEmployee()) {
            return true;
        }

        return $user->isCustomer() && $payment->rental->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin() || $user->isEmployee() || $user->isCustomer();
    }

    public function update(User $user, Payment $payment): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin() || $user->isEmployee()) {
            return true;
        }

        return $user->isCustomer() && $payment->rental->user_id === $user->id && $payment->status === PaymentStatus::Pending->value;
    }

    public function delete(User $user, Payment $payment): bool
    {
        return $user->isSuperAdmin();
    }

    public function restore(User $user, Payment $payment): bool
    {
        return $user->isSuperAdmin();
    }

    public function forceDelete(User $user, Payment $payment): bool
    {
        return $user->isSuperAdmin();
    }
}
