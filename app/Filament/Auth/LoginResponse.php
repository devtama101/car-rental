<?php

namespace App\Filament\Auth;

use Filament\Auth\Http\Responses\Contracts\LoginResponse as Responsable;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse implements Responsable
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        $user = auth()->user();

        if ($user?->isCustomer()) {
            return redirect()->to('/dashboard');
        }

        if ($user?->isEmployee()) {
            return redirect()->to('/employee');
        }

        if ($user?->isSuperAdmin() || $user?->isAdmin()) {
            return redirect()->to('/admin');
        }

        return redirect()->to('/');
    }
}
