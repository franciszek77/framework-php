<?php
namespace MyFramework\Core;

class Installer {
    public static function postInstall() {
        // Crear directorios
        $dirs = [
            '../../frontend/pages',              // Ajustado: desde backend/core/, retrocedemos dos niveles
            '../../frontend/assets/css',
            '../../frontend/assets/js',
            '../../public',
            '../../frontend/templates',
            '../../frontend/assets/bootstrap/css',
            '../../frontend/assets/bootstrap/js',
            '../../frontend/assets/css',
            '../../frontend/assets/js'
        ];
        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
        }

        // Copiar todas las plantillas a frontend/templates/ inicialmente
        $templates = [
            '../templates/config.html' => '../../frontend/config.html', // Ajustado: retrocedemos un nivel para backend/templates/
            '../templates/index.php' => '../../public/index.php',
            '../templates/api.php' => '../../public/api.php',
            '../templates/main.html' => '../../frontend/templates/main.html'                        
        ];
        foreach ($templates as $source => $dest) {
            if (!file_exists($dest)) {
                copy($source, $dest);
            }
        }

        // Mover archivos específicos a sus ubicaciones finales
        if (file_exists('../../frontend/templates/main.html') && !file_exists('../../frontend/pages/main.html')) {
            rename('../../frontend/templates/main.html', '../../frontend/pages/main.html');
        }
        
        // Copiar archivos de Bootstrap desde vendor/
        $bootstrap_files = [
            '../../vendor/twbs/bootstrap/dist/css/bootstrap.min.css' => '../../frontend/assets/bootstrap/css/bootstrap.min.css', // Ajustado
            '../../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js' => '../../frontend/assets/bootstrap/js/bootstrap.bundle.min.js'
        ];
        foreach ($bootstrap_files as $source => $dest) {
            if (file_exists($source) && !file_exists($dest)) {
                copy($source, $dest);
            }
        }

        // Crear archivo de configuración inicial
        if (!file_exists('../config.json')) { // Ajustado: desde backend/core/, retrocedemos un nivel para backend/
            file_put_contents('../config.json', json_encode([
                'host' => 'localhost',
                'user' => 'root',
                'pass' => '',
                'name' => 'framework'
            ]));
        }
    }
}

// Ejecutar postInstall directamente si el archivo se ejecuta desde la terminal
if (php_sapi_name() === 'cli') {
    require_once __DIR__ . '/../../vendor/autoload.php';
    Installer::postInstall();
    echo "Instalación completada.\n";
}