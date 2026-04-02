<?php

namespace App\Services\Auth;

use Laravel\Socialite\Facades\Socialite;

class GoogleAuthService
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function getUser()
    {
        return Socialite::driver('google')->user();
    }
}