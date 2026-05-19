<?php

namespace App\Providers;

use App\Filament\Auth\LoginResponse;
use App\Filament\Auth\LogoutResponse;
use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;
use Filament\Auth\Http\Responses\Contracts\LogoutResponse as LogoutResponseContract;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->app->bind(
            LoginResponseContract::class,
            LoginResponse::class,
        );

        $this->app->bind(
            LogoutResponseContract::class,
            LogoutResponse::class,
        );

        FilamentColor::register([
            'primary' => Color::Green,
            'warning' => Color::Amber,
        ]);
    }
}
