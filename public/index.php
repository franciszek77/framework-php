<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../backend/core/Render.php';
use MyFramework\Core\Render;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Framework PHP</title>
    
    <script>
        window.MyEndpointRoot = '<?php echo \MyFramework\Core\Config::$api_base; ?>';
    </script>
</head>
<body>
    <div id="app"></div>
    <?php Render::run(); ?>
    
</body>
</html>