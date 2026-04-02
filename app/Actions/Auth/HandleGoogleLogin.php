<?php

namespace App\Actions\Auth;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class HandleGoogleLogin
{
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    public function execute($googleUser)
    {
        // 1. cek user by google_id
        $user = $this->userRepository->findByGoogleId($googleUser->id);

        if ($user) {
            Auth::login($user);
            return $user;
        }

        // 2. cek by email
        $user = $this->userRepository->findByEmail($googleUser->email);

        if ($user) {
            $this->userRepository->update($user, [
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
            ]);

            Auth::login($user);
            return $user;
        }

        // 3. create user baru
        $user = $this->userRepository->create([
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'google_id' => $googleUser->id,
            'avatar' => $googleUser->avatar,
            'password' => bcrypt(str()->random(16)),
        ]);

        Auth::login($user);

        return $user;
    }
}