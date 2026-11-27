<?php
abstract class Controller
{
    protected function render(string $view, array $data = []): void
    {
        $data['title'] = $data['title'] ?? '';
        extract($data);

        $loggedIn = $this->userId() !== null;
        $username = $_SESSION['username'] ?? '';
        $promptUser = $loggedIn ? $username : 'guest';
        $flash    = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        ob_start();
        require __DIR__ . "/../Views/$view.php";
        $content = ob_get_clean();

        require __DIR__ . '/../Views/layout.php';
    }

    protected function redirect(string $path): void
    {
        header("Location: $path");
        exit;
    }

    protected function flash(string $message): void
    {
        $_SESSION['flash'] = $message;
    }

    protected function userId(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    protected function requireLogin(): void
    {
        if ($this->userId() === null) {
            $this->flash('log in first >.<');
            $this->redirect('/login');
        }
    }
}
