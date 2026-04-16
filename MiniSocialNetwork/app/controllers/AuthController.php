<?php

class AuthController
{
    private function redirect($route)
    {
        $base = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']);
        header("Location: $base?route=$route");
        exit;
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
            return;

        $username = trim($_POST['username']);
        $password = $_POST['password'];

        if (empty($username) || empty($password)) {
            $this->redirect('login&error=Please fill all fields');
        }

        $userModel = new UserModel();
        $user = $userModel->findUserByUsername($username);

        if (!$user) {
            echo '<script>alert("User not found!"); window.location.href="?route=login";</script>';
            exit;
        }

        if (!password_verify($password, $user['password'])) {
            echo '<script>alert("Wrong password!"); window.location.href="?route=login";</script>';
            exit;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['profile_image'] = $user['profile_image'] ?? 'default.png';

        $this->redirect('feed');
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        $this->redirect('login');
    }

    public function signup()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
            return;

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm-password'] ?? '';

        if (empty($username) || empty($password) || empty($confirmPassword)) {
            $this->redirect('signup&error=Please fill all fields');
        }

        if ($password !== $confirmPassword) {
            echo '<script>alert("Passwords do not match!"); window.location.href="?route=signup";</script>';
            exit;
        }

        $userModel = new UserModel();

        if ($userModel->findUserByUsername($username)) {
            $this->redirect('signup&error=Username already taken');
        }

        $userId = $userModel->insertUser($username, $password);

        if ($userId) {
            echo '<script>alert("Registration successful!"); window.location.href="?route=login";</script>';
        } else {
            $this->redirect('signup&error=Registration failed');
        }
        exit;
    }
}
