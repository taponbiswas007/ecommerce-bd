<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    // Google Login
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'email_verified_at' => now(),
                    'password' => bcrypt(rand(100000, 999999)),
                ]);
            } else {
                $user->update(['google_id' => $googleUser->id]);
            }

            Auth::login($user);

            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google login failed');
        }
    }

    // Facebook Login
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();

            $user = User::where('email', $facebookUser->email)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $facebookUser->name,
                    'email' => $facebookUser->email,
                    'facebook_id' => $facebookUser->id,
                    'email_verified_at' => now(),
                    'password' => bcrypt(rand(100000, 999999)),
                ]);
            } else {
                $user->update(['facebook_id' => $facebookUser->id]);
            }

            Auth::login($user);

            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Facebook login failed');
        }
    }
}
