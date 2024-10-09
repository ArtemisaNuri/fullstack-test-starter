<?php

header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Origin: *');

// Handle pre-flight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Initialize Dotenv only once
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Define base path
define('BASE_PATH', dirname(__DIR__) . '/');

include_once BASE_PATH . 'src/helpers.php';

// Setup FastRoute dispatcher
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->post('/graphql', [App\GraphQL\Controller::class, 'handle']);
});

$routeInfo = $dispatcher->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI']
);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        if (preg_match('/\.(?:css|js|png|jpg|jpeg|gif|ico)$/', $_SERVER['REQUEST_URI'])) {
            setMimeType($_SERVER['REQUEST_URI']);
            readfile(BASE_PATH . 'public' . $_SERVER['REQUEST_URI']);
            exit;
        }

        require(BASE_PATH . 'public/index.html');
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        header("HTTP/1.1 405 Method Not Allowed");
        header("Allow: " . implode(", ", $allowedMethods));
        echo json_encode(["error" => "Method not allowed. Allowed methods: " . implode(", ", $allowedMethods)]);
        break;

    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        echo $handler($vars);
        break;
}

function setMimeType($filename)
{
    $mime_types = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'ico' => 'image/x-icon',
    ];

    $ext = pathinfo($filename, PATHINFO_EXTENSION);

    if (array_key_exists($ext, $mime_types)) {
        header('Content-Type: ' . $mime_types[$ext]);
    } else {
        header('Content-Type: application/octet-stream');
    }
}
