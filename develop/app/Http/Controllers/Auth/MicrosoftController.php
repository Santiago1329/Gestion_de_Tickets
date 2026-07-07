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
    public function redirect()
    {
        return Socialite::driver('microsoft')->redirect();
    }

    public function callback()
    {
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