<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Gagal login dengan Google. Silakan coba lagi.']);
        }

        $user = User::where('google_id', $googleUser->getId())->first();

        if (!$user) {
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'auth_provider' => 'google',
                    'auth_provider_id' => $googleUser->getId(),
                ]);
            } else {
                $user = User::create([
                    'uuid' => Str::uuid(),
                    'google_id' => $googleUser->getId(),
                    'auth_provider' => 'google',
                    'auth_provider_id' => $googleUser->getId(),
                    'nama' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'role' => 'customer',
                    'status' => 'active',
                    'email_verified_at' => now(),
                    'password' => bcrypt(Str::random(24)),
                ]);
            }
        }

        Auth::login($user);
        $request = request();
        $user->last_login_at = now();
        $user->last_login_ip = $request->ip();
        $user->save();

        return redirect()->intended('/');
    }
}
