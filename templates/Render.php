<?php
namespace MyFramework\Core;

use MyFramework\Core\Config;
use MyFramework\Core\Database;
use MyFramework\Core\Router;
use MyFramework\Core\Enqueue;

class Render {
    private static $config;
    private static $db;
    private static $router;

    public static function run() {
        self::$config = require __DIR__ . '/../../backend/config/controller.php';
        Config::load_db_config();
        self::$db = new Database(Config::$db_config);
        self::$router = new Router();
        foreach (self::$config['modules'] as $route => $class) {
            self::$router->add_route($route, ["MyFramework\\Modules\\{$class}", 'handle']);
        }
        Enqueue::load_default_frameworks();
        $page = isset($_GET['page']) ? trim($_GET['page']) : 'main';
        $page_file = __DIR__ . '/../../frontend/pages/' . $page . '.html';
        if (!file_exists($page_file)) {
            error_log("Página no encontrada: $page_file, usando main como fallback");
            $page = 'main';
        } else {
            error_log("Cargando página: $page_file");
        }
        self::render($page);
    }

    private static function render($page) {
        $page_file = __DIR__ . '/../../frontend/pages/' . $page . '.html';
        $header = isset($_GET['header']) ? $_GET['header'] : 'header';
        $footer = isset($_GET['footer']) ? $_GET['footer'] : 'footer';

        // Definir rutas absolutas con la extensión correcta
        $header_file = __DIR__ . '/../../frontend/templates/headers/' . $header . '.html';
        $footer_file = __DIR__ . '/../../frontend/templates/footers/' . $footer . '.html';

        // Depuración
        if (!file_exists($header_file)) {
            error_log("Header no encontrado: $header_file");
            echo "<!-- Debug: Archivo de header no encontrado: $header_file -->";
        }
        if (!file_exists($footer_file)) {
            error_log("Footer no encontrado: $footer_file");
            echo "<!-- Debug: Archivo de footer no encontrado: $footer_file -->";
        }

        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Framework PHP - <?php echo ucfirst(str_replace('.html', '', $page)); ?></title>
            <?php echo Enqueue::render_styles(); ?>
            <script>
                if (typeof window.MyEndpointRoot === 'undefined') {
                    window.MyEndpointRoot = '<?php echo Config::$api_base; ?>';
                }
            </script>
        </head>
        <body>
            <div class="container">
                <?php
                if (file_exists($header_file)) {
                    include $header_file;
                    error_log("Header incluido: $header_file");
                } else {
                    echo "<!-- Error: No se pudo incluir el header -->";
                }
                ?>
                <?php if (file_exists($page_file)) include $page_file; ?>
                <?php
                if (file_exists($footer_file)) {
                    include $footer_file;
                    error_log("Footer incluido: $footer_file");
                } else {
                    echo "<!-- Error: No se pudo incluir el footer -->";
                }
                ?>
            </div>
            <?php echo Enqueue::render_scripts(); ?>
        </body>
        </html>
        <?php
    }
}