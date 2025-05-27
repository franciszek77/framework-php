<?php
namespace MyFramework\Core;

class Config {
    public static $db_config;
    public static $api_base;

    public static function load_db_config() {
        // Intentar leer .env con manejo de errores
        $env_path = __DIR__ . '/../config/.env';
        $env = [];
        if (file_exists($env_path)) {
            $env = parse_ini_file($env_path, false, INI_SCANNER_RAW);
            if ($env === false) {
                error_log("Error al parsear el archivo .env en $env_path");
            }
        } else {
            error_log("Archivo .env no encontrado en $env_path. Usando valores por defecto.");
        }

        // Asignar configuración con valores por defecto
        self::$db_config = [
            'host' => $env['DB_HOST'] ?? 'localhost',
            'user' => $env['DB_USER'] ?? 'root',
            'pass' => $env['DB_PASS'] ?? '',
            'name' => $env['DB_NAME'] ?? 'framework' // Corregido a 'framework' según tu base de datos
        ];
        self::$api_base = '/framework-php/public/api/';
    }
}