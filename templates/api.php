<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../vendor/autoload.php';

use MyFramework\Core\Database;
use MyFramework\Core\Router;
use MyFramework\Core\Config;

// Cargar configuración antes de usar Database
Config::load_db_config();

$router = new Router();
$db = new Database(Config::$db_config);

$controller_config = require __DIR__ . '/../backend/config/controller.php';
foreach ($controller_config['modules'] as $route => $class) {
    $router->add_route($route, ["MyFramework\\Modules\\{$class}", 'handle']);
}

$path = isset($_GET['path']) ? trim($_GET['path'], '/') : '';
if (empty($path)) {
    $request_uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $base_path = trim($controller_config['api_base'], '/');
    if ($base_path && strpos($request_uri, $base_path) === 0) {
        $path = substr($request_uri, strlen($base_path));
        $path = trim($path, '/');
    }
    error_log("Path inferido desde REQUEST_URI: $path");
}
$method = $_SERVER['REQUEST_METHOD'];
error_log("API.php - Recibida solicitud: Path: $path, Method: $method, POST: " . print_r($_POST, true));
$response = $router->dispatch($path, $method, $db);

echo json_encode($response);