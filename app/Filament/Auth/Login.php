<?php

namespace App\Filament\Auth;

use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    public function authenticate(): ?LoginResponse
    {
        return parent::authenticate();
    }

    public function getTitle(): string | Htmlable
    {
        return __('Masuk');
    }

    public function getHeading(): string | Htmlable | null
    {
        return __('Masuk ke Akun Anda');
    }
}
