<?php
session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
]);

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function short($value, int $max = 80): string
{
    $value = (string) $value;
    preg_match('/^.{0,' . $max . '}/su', $value, $m);
    $cut = $m[0] ?? substr($value, 0, $max);
    return $cut === $value ? $value : $cut . '...';
}

spl_autoload_register(function (string $class): void {
    foreach (['Core', 'Models', 'Controllers'] as $dir) {
        $file = __DIR__ . "/../app/$dir/$class.php";
        if (is_file($file)) {
            require $file;
            return;
        }
    }
});

// "METHOD /path" => [Controller, method]
$routes = [
    'GET /'              => ['ToolController', 'home'],
    'GET /tools'         => ['ToolController', 'index'],
    'POST /tools'        => ['ToolController', 'convert'],

    'GET /login'         => ['AuthController', 'loginForm'],
    'POST /login'        => ['AuthController', 'login'],
    'GET /register'      => ['AuthController', 'registerForm'],
    'POST /register'     => ['AuthController', 'register'],
    'GET /logout'        => ['AuthController', 'logout'],

    'GET /saved'         => ['EntryController', 'index'],
    'POST /saved'        => ['EntryController', 'store'],
    'POST /saved/delete' => ['EntryController', 'destroy'],
];

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$key  = $_SERVER['REQUEST_METHOD'] . ' ' . $path;

if (!isset($routes[$key])) {
    http_response_code(404);
    echo 'nothing cooking here (404) >.<';
    exit;
}

[$class, $method] = $routes[$key];
(new $class())->$method();
