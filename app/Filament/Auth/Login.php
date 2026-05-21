<?php

namespace App\Filament\Auth;

use App\Enums\PersonType;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Facades\Filament;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    public function authenticate(): mixed
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $data = $this->form->getState();

        $authGuard = Filament::auth();
        $authProvider = $authGuard->getProvider();
        $credentials = $this->getCredentialsFromFormData($data);

        $user = $authProvider->retrieveByCredentials($credentials);

        if ((! $user) || (! $authProvider->validateCredentials($user, $credentials))) {
            $this->throwFailureValidationException();
        }

        if (! $authGuard->attemptWhen($credentials, function (Authenticatable $user): bool {
            return true;
        }, $data['remember'] ?? false)) {
            $this->throwFailureValidationException();
        }

        session()->regenerate();

        $type = DB::table('people')
            ->where('user_id', auth()->id())
            ->whereNull('deleted_at')
            ->value('type');

        $panel = match ($type) {
            PersonType::SuperAdmin->value, PersonType::Admin->value => 'admin',
            PersonType::Employee->value => 'employee',
            default => 'customer',
        };

        $this->redirectIntended(Filament::getUrl($panel));
    }

    public function getTitle(): string | Htmlable
    {
        return __('Masuk');
    }

    public function getHeading(): string | Htmlable | null
    {
        return __('Masuk ke Akun Anda');
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.email' => __('Kredensial yang diberikan tidak dapat ditemukan.'),
        ]);
    }
}
