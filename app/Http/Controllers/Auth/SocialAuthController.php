<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

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
            return redirect()->route('login')->withErrors(['error' => 'Gagal masuk dengan Google. Silakan coba lagi.']);
        }

        $socialAccount = SocialAccount::where('provider', 'google')
            ->where('provider_id', $googleUser->getId())
            ->first();

        if ($socialAccount) {
            $user = $socialAccount->user;
            Auth::login($user);

            return redirect()->intended(route('home'));
        }

        // Check if user with that email already exists
        $user = User::where('email', $googleUser->getEmail())->first();

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->getName() ?? 'Pelanggan Google',
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(Str::random(24)),
                'avatar' => $googleUser->getAvatar(),
                'role' => 'customer',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
        }

        SocialAccount::create([
            'user_id' => $user->id,
            'provider' => 'google',
            'provider_id' => $googleUser->getId(),
        ]);

        Auth::login($user);

        return redirect()->intended(route('home'))->with('success', 'Selamat datang, '.$user->name.'!');
    }
}
