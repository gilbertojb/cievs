<?php

namespace Cievs\Application\Auth;

class Auth
{
    public function user()
    {
//        return User::find(isset($_SESSION['user']) ? $_SESSION['user'] : 0);
    }

    public function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public function attempt($email, $password): bool
    {
//        $user = User::where('email', $email)->first();
//
//        if (! $user) {
//            return false;
//        }
//
//        if (password_verify($password, $user->password)) {
//            $_SESSION['user'] = $user->id;
//            return true;
//        }
//
        return false;
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
    }
}