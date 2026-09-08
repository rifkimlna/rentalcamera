<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SocialAuthController extends Controller
{
    public function redirectToGoogle(Request $request)
    {
        $redirect = trim($request->getSchemeAndHttpHost() . '/auth/google/callback');
        return Socialite::driver('google')->redirectUrl($redirect)->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $redirect = trim($request->getSchemeAndHttpHost() . '/auth/google/callback');
            $googleUser = Socialite::driver('google')->redirectUrl($redirect)->user();
        } catch (\Exception $e) {
            Log::error('Google OAuth callback error: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('login')->withErrors(['email' => 'Gagal login dengan Google. Silakan coba lagi. (' . $e->getMessage() . ')']);
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
        $user->last_login_at = now();
        $user->last_login_ip = $request->ip();
        $user->save();

        return redirect()->intended('/');
    }
}
