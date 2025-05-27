<?php
if (php_sapi_name() === 'cli') {
    die("Este script debe ejecutarse desde un navegador. Accede a http://localhost/framework-php/install.php\n");
}

if (file_exists(__DIR__ . '/backend/config/.env') && !isset($_GET['force'])) {
    die('El framework ya está instalado. Usa ?force para reinstalar.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db_host = $_POST['db_host'];
    $db_user = $_POST['db_user'];
    $db_pass = $_POST['db_pass'] ?? '';
    $db_name = $_POST['db_name'];

    // Crear estructura de directorios
    $dirs = [
        'backend/core',
        'backend/modules',
        'backend/config',
        'frontend/pages',
        'frontend/assets/images',
        'frontend/assets/bootstrap/css',
        'frontend/assets/bootstrap/js',
        'frontend/assets/js',
        'frontend/templates/headers',
        'frontend/templates/footers',
        'public'
    ];
    foreach ($dirs as $dir) {
        if (!file_exists(__DIR__ . '/' . $dir)) {
            mkdir(__DIR__ . '/' . $dir, 0777, true);
        }
    }

    // Copiar Bootstrap desde vendor/
    $bootstrap_source = __DIR__ . '/vendor/twbs/bootstrap/dist/';
    $bootstrap_dest = __DIR__ . '/frontend/assets/bootstrap/';
    if (file_exists($bootstrap_source)) {
        if (!file_exists($bootstrap_dest . 'css')) mkdir($bootstrap_dest . 'css', 0777, true);
        if (!file_exists($bootstrap_dest . 'js')) mkdir($bootstrap_dest . 'js', 0777, true);
        if (file_exists($bootstrap_source . 'css/bootstrap.min.css')) {
            copy($bootstrap_source . 'css/bootstrap.min.css', $bootstrap_dest . 'css/bootstrap.min.css');
        }
        if (file_exists($bootstrap_source . 'js/bootstrap.bundle.min.js')) {
            copy($bootstrap_source . 'js/bootstrap.bundle.min.js', $bootstrap_dest . 'js/bootstrap.bundle.min.js');
        }
    } else {
        error_log("Directorio vendor/twbs/bootstrap no encontrado. Asegúrate de ejecutar 'composer install'.");
    }

    // Copiar archivos desde templates/
    $file_mappings = [
        'Config.php' => 'backend/core/Config.php',
        'Database.php' => 'backend/core/Database.php',
        'Enqueue.php' => 'backend/core/Enqueue.php',
        'Render.php' => 'backend/core/Render.php',
        'Router.php' => 'backend/core/Router.php',
        'example.php' => 'backend/modules/example.php',
        'main.html' => 'frontend/pages/main.html',
        'example.js' => 'frontend/assets/js/example.js',
        'header.html' => 'frontend/templates/headers/header.html', 
        'footer.html' => 'frontend/templates/footers/footer.html', 
        'logo.png' => 'frontend/assets/images/logo.png', 
        'index.php' => 'public/index.php',
        'api.php' => 'public/api.php',
        '.htaccess' => 'public/.htaccess',
        'readme.md' => 'readme.md',
        '.env' => 'backend/config/.env'
    ];
    foreach ($file_mappings as $source_file => $dest_file) {
        $source_path = __DIR__ . '/templates/' . $source_file;
        $dest_path = __DIR__ . '/' . $dest_file;
        if (file_exists($source_path)) {
            copy($source_path, $dest_path);
        } else {
            error_log("Archivo $source_path no encontrado en templates/.");
        }
    }

    // Crear o actualizar backend/config/.env
    $env_content = <<<EOD
DB_HOST=$db_host
DB_USER=$db_user
DB_PASS=$db_pass
DB_NAME=$db_name
EOD;
    if (!file_exists(__DIR__ . '/backend/config')) {
        mkdir(__DIR__ . '/backend/config', 0777, true);
    }
    file_put_contents(__DIR__ . '/backend/config/.env', $env_content);

    // Crear o actualizar backend/config/controller.php (opcional, para compatibilidad)
    $config_content = <<<EOD
<?php
return [
    'modules' => [
        'login' => 'Login',
        'usuario' => 'Usuario',
        'example' => 'Example',
    ],
    'api_base' => '/framework-php/public/api/',
];
EOD;
    file_put_contents(__DIR__ . '/backend/config/controller.php', $config_content);

    // Conectar y crear tabla users
    try {
        $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
        $db->exec("CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL,
            password VARCHAR(255) NOT NULL
        )");
        echo "Instalación completada con éxito. La estructura del proyecto ha sido creada, Bootstrap copiado, y los archivos de templates movidos.";
        header("refresh:3;url=http://localhost/framework-php/public/");
    } catch (PDOException $e) {
        echo "Error al conectar a la base de datos: " . $e->getMessage();
    }
} else {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Instalación del Framework</title>
    </head>
    <body>
        <div class="container">
            <h2>Configuración de la Base de Datos</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Host:</label>
                    <input type="text" name="db_host" class="form-control" value="localhost" required>
                </div>
                <div class="form-group">
                    <label>Usuario:</label>
                    <input type="text" name="db_user" class="form-control" value="root" required>
                </div>
                <div class="form-group">
                    <label>Contraseña:</label>
                    <input type="password" name="db_pass" class="form-control">
                </div>
                <div class="form-group">
                    <label>Nombre de la Base de Datos:</label>
                    <input type="text" name="db_name" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Instalar</button>
            </div>
        </div>
    </body>
    </html>
    <?php
}
?>