<?php

namespace App\Lib;

use App\Models\User;

class Session {
    private $user = false;

    public function __construct() {
        session_start();
        if (isset($_SESSION['user'])) {
            $this->user = $_SESSION['user'];
        }
    }

    public function isLoggedIn() {
        return $this->user;
    }

    public function getUser() {
        return $this->user;
    }

    public function login(User $userObj) {
        $this->user = $userObj;
        $_SESSION['user'] = $userObj;
        return true;
    }

    public function logout() {
        $this->user = false;
        $_SESSION = [];
        session_destroy();
        return true;
    }
}