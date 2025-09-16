<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(array $credentials, bool $remember = false): void
    {
        if (!Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }
    }

    public function logout(): void
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}
