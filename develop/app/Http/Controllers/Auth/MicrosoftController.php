<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class MicrosoftController extends Controller
{
    protected function setMicrosoftRedirect(): void
    {
        $redirect = config('services.microsoft.redirect');
        $redirectAlt = config('services.microsoft.redirect_alt');

        if ($redirectAlt && request()->getHost() === '127.0.0.1') {
            $redirect = $redirectAlt;
        }

        config(['services.microsoft.redirect' => rtrim($redirect, '/')]);
    }

    public function redirect()
    {
        $this->setMicrosoftRedirect();

        return Socialite::driver('microsoft')->redirect();
    }

    public function callback()
    {
        $this->setMicrosoftRedirect();

        try {
            $microsoftUser = Socialite::driver('microsoft')->user();
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('login')->withErrors([
                'microsoft' => 'No se pudo completar el inicio de sesión con Microsoft.',
            ]);
        }

        $user = User::where('email', $microsoftUser->getEmail())->first();

        if (! $user) {
            $user = User::create([
                'name' => $microsoftUser->getName() ?? $microsoftUser->getEmail(),
                'email' => $microsoftUser->getEmail(),
                'password' => Hash::make(Str::random(24)),
                'rol' => 'cliente',
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user, true);

        return redirect()->route('dashboard');
    }
}