<?php

namespace App\User;

class LoginService 
{
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function attempt($username, $password)
    {
        $user = $this->userRepository->findByUsername($username);
        if(empty($user)){
            return false;
        }

        if (password_verify($password, $user->password)) {
            $_SESSION['login'] = $user->username;
            session_regenerate_id(true);
            return true;
        }else {
            return false;
        }
    }
    public function logout()
    {
        unset($_SESSION['login']);
        session_regenerate_id(true);
    }

    public function check()
    {
        // wenn die $_session an der stelle 'login' gesetzt ist, dann ist der user eingeloggt
        if (isset($_SESSION['login'])) {
            return true;
        }else {
            header("Location: login");
            die();
        }
    }
}