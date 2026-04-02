<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Auth\GoogleAuthService;
use App\Actions\Auth\HandleGoogleLogin;

class GoogleAuthController extends Controller
{
    public function __construct(
        protected GoogleAuthService $googleService,
        protected HandleGoogleLogin $handleGoogleLogin
    ) {}

    public function redirect()
    {
        return $this->googleService->redirect();
    }

    public function callback()
    {
        $googleUser = $this->googleService->getUser();
        $this->handleGoogleLogin->execute($googleUser);
        return redirect('/dashboard');
    }
}
