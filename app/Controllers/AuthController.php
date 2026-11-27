<?php

class AuthController extends Controller
{
    public function loginForm(): void
    {
        $this->render('login', ['title' => 'login', 'error' => null]);
    }

    public function login(): void
    {
        $user = User::login((string) ($_POST['username'] ?? ''), (string) ($_POST['password'] ?? ''));

        if ($user === null) {
            $this->render('login', ['title' => 'login', 'error' => 'wrong username or password >.<']);
            return;
        }

        $this->startSession($user['id'], $user['username']);
        $this->flash('welcome back!');
        $this->redirect('/tools');
    }

    public function registerForm(): void
    {
        $this->render('register', ['title' => 'register', 'error' => null]);
    }

    public function register(): void
    {
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $error    = null;

        if (!preg_match('/^[A-Za-z0-9_]{3,32}$/', $username)) {
            $error = 'username: 3-32 chars, letters, numbers and _ only >.<';
        } elseif (strlen($password) < 8) {
            $error = 'password must be at least 8 characters >.<';
        } elseif (!User::create($username, $password)) {
            $error = 'that username is taken >.<';
        }

        if ($error !== null) {
            $this->render('register', ['title' => 'register', 'error' => $error]);
            return;
        }

        $user = User::login($username, $password);
        $this->startSession($user['id'], $user['username']);
        $this->flash('account created, welcome!');
        $this->redirect('/tools');
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: /tools');
        exit;
    }

    private function startSession($id, $username): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id']  = (int) $id;
        $_SESSION['username'] = $username;
    }
}
