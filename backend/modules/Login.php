<?php
namespace MyFramework\Modules;

use MyFramework\Core\Database;

class Login {
    public static function handle($db) {
        error_log("Login - Procesando solicitud: Method: {$_SERVER['REQUEST_METHOD']}, POST: " . print_r($_POST, true));
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method !== 'POST') {
            return ['success' => false, 'message' => 'Método no permitido'];
        }

        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';

        if (empty($username) || empty($password)) {
            return ['success' => false, 'message' => 'Usuario y contraseña son requeridos'];
        }

        $stmt = $db->query("SELECT * FROM users WHERE username = ?", [$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return ['success' => true, 'message' => 'Login exitoso'];
        }

        return ['success' => false, 'message' => 'Credenciales incorrectas'];
    }
}