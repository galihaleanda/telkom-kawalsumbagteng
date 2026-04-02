<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function findByGoogleID(string $googleId) {
        return User::where("", $googleId)->first();
        }
    public function findByEmail(string $email) {
        return User::where("email", $email)->first();   
        }

        public function create(array $data) {
            return User::create($data);
        }

        public function update(User $user, array $data) : User {
            $user->update($data);
            return $user;
        }       
}
