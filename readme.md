# Framework PHP

![Framework PHP Logo](https://via.placeholder.com/150) <!-- Reemplaza con tu logo si lo tienes -->

> Un framework ligero y sencillo para desarrollar aplicaciones web en PHP con soporte para CRUD y API RESTful.

---

## Introducción

Este framework está diseñado para ser fácil de usar, permitiendo a los programadores crear páginas y módulos con solo tres archivos: un HTML, un JavaScript/jQuery, y un PHP para manejar la lógica del backend. Ideal para proyectos rápidos y escalables.

- **Versión**: 1.0.0
- **Última actualización**: 26 de mayo de 2025
- **[Repositorio](https://github.com/franciszek77/framework-php)**

---

## Instalación

### Requisitos
- PHP con soporte para MySQLi
- Servidor web (WAMP, XAMPP, etc.)
- Composer instalado (`composer --version`)

### Pasos
1. Descomprime el archivo `.rar` en tu directorio de servidor (ejemplo: `C:\wamp64\www\framework-php`).
2. Ejecuta `composer install` en la terminal desde el directorio raíz.
3. Crea una base de datos MySQL (ejemplo: `framework`) y anota su nombre, usuario y contraseña.
4. Accede a `http://localhost/framework-php/install.php`, ingresa los datos de la base de datos, y guarda.
5. Serás redirigido a `http://localhost/framework-php/public/main`. ¡Listo para programar!

**Tiempo estimado**: Menos de 2 minutos.

### Requisitos

- PHP con soporte para MySQLi.
- Servidor web (como WAMP o XAMPP).
- Composer instalado.

**Tiempo estimado**: Menos de 2 minutos.

---

## Estructura de Directorios

Tras ejecutar `install.php`, el framework tendrá esta estructura:

| Directorio         | Descripción                                      |
|--------------------|--------------------------------------------------|
| `backend/`         | Contiene la lógica del servidor (módulos, config) |
| `frontend/`        | Contiene páginas HTML y assets (JS, CSS)         |
| `public/`          | Contiene los puntos de entrada (API, index)      |
| `templates/`       | Plantillas iniciales generadas por `install.php` |
| `vendor/`          | Dependencias de Composer (generado automáticamente) |

---

## Creación de un Módulo PHP

Los módulos se colocan en `backend/modules/` con el namespace `MyFramework\Modules`. Ejemplo: `example.php`.

### Código de Ejemplo
```php
<?php
namespace MyFramework\Modules;
use MyFramework\Core\Database;

class Example {
    private $db;
    public function __construct() { $this->db = new Database(\MyFramework\Core\Config::$db_config); }
    public function create($username, $password) {
        $data = ["username" => $username, "password" => password_hash($password, PASSWORD_DEFAULT)];
        return $this->db->insert("users", $data);
    }
    // ... (otros métodos CRUD)
    public static function handle() {
        header("Content-Type: application/json");
        $method = $_SERVER["REQUEST_METHOD"];
        $example = new self();
        // Lógica de manejo de solicitudes
    }
}

Nota: Este archivo (example.php) es un ejemplo de referencia para operaciones CRUD. No lo elimines, ya que te servirá como guía para crear nuevos módulos.

``` 
Creación y Acceso a Páginas HTML
Crear una Página
Coloca tus archivos en frontend/pages/ (puedes usar subdirectorios como frontend/pages/auth/).
Usa clases de Bootstrap para estilos (ya incluido por defecto).
Ejemplo: frontend/pages/mi_pagina.html 

<div class="container">
    <h2>Mi Página</h2>
    <p class="text-success">Ejemplo con Bootstrap.</p>
    <button class="btn btn-primary">Click me</button>
</div>

Acceder a Páginas
URL base: http://localhost/framework-php/public/ (muestra main.html).
Con subdirectorios: http://localhost/framework-php/public/auth/mi_pagina.

``` 
Creación de un Script JavaScript
Coloca tus scripts en frontend/assets/js/. Ejemplo: example.js.

Código de Ejemplo
javascript

jQuery(document).ready(function($) {
    function createUser(username, password) {
        $.ajax({
            url: window.MyEndpointRoot + "example",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ username, password }),
            success: function(response) { console.log("Usuario creado:", response); }
        });
    }
    // ... (otros métodos CRUD)
});

Nota: Siempre usa window.MyEndpointRoot como prefijo en las URLs de AJAX. Esto asegura que las solicitudes se dirijan correctamente al backend.

Encolar scripts y estilos
Puedes encolar scripts y estilos personalizados editando backend/core/Enqueue.php.

Ejemplo de encolado
Encolar un script:
php

Copiar
self::enqueue_script("mi_script", "/framework-php/frontend/assets/js/mi_script.js", ["jquery"], true);
Encolar un estilo:
php

Copiar
self::enqueue_style("mi_estilo", "/framework-php/frontend/assets/css/mi_estilo.css");
Nota sobre Bootstrap:

Bootstrap está encolado por defecto (CSS y JS). Si no lo vas a usar, comenta o elimina las siguientes líneas en Enqueue.php:
php

Copiar
self::enqueue_style('bootstrap', '/framework-php/frontend/assets/bootstrap/css/bootstrap.min.css');
self::enqueue_script('bootstrap', '/framework-php/frontend/assets/bootstrap/js/bootstrap.bundle.min.js');
Configurar el manejador de endpoints
Edita backend/config/controller.php para agregar nuevos endpoints a tu API.

Ejemplo: controller.php
php

Copiar

<?php
return [
    'modules' => [
        'login' => 'Login',
        'usuario' => 'Usuario',
        'example' => 'Example',
    ],
    'api_base' => '/framework-php/public/api/',
];

Clave (login): Define el nombre del endpoint. Por ejemplo, window.MyEndpointRoot + "login" apunta a /framework-php/public/api/login.
Valor (Login): Indica el nombre de la clase PHP en backend/modules/ que manejará las solicitudes (en este caso, Login.php).
Nota importante: Siempre usa window.MyEndpointRoot + "nombre_endpoint" en tus solicitudes AJAX. El backend está configurado para recibir las URLs de esta manera.

Uso del endpoint
El archivo backend/modules/example.php contiene ejemplos completos de cómo realizar operaciones CRUD con este framework. No lo elimines, ya que sirve como referencia para programar nuevos módulos PHP de manera correcta.

Crear (POST): Inserta un nuevo registro.
Leer (GET): Consulta todos los registros o uno específico.
Actualizar (PUT): Modifica un registro existente.
Eliminar (DELETE): Borra un registro.
Consulta example.php para ver cómo estructurar tus propios módulos CRUD.

Nota final: Pasos simples para programar
Para crear una nueva página con funcionalidad en este framework, solo necesitas tres archivos:

HTML (frontend/pages/mi_pagina.html):
Diseña tu página usando clases de Bootstrap si deseas estilos predefinidos.
Ejemplo: frontend/pages/auth/login.html.
JavaScript/jQuery (frontend/assets/js/mi_script.js):
Usa el endpoint correctamente en la URL de AJAX (por ejemplo, window.MyEndpointRoot + "mi_endpoint").
Ejemplo: frontend/assets/js/login.js.
PHP para CRUD (backend/modules/MiModulo.php):
Implementa los métodos CRUD necesarios (ver example.php como referencia).
Ejemplo: backend/modules/Login.php.
CSS personalizado (opcional):
Si deseas estilos personalizados, crea frontend/assets/css/mi_estilo.css y encólalo en Enqueue.php:
php

Copiar
self::enqueue_style('mi_estilo', '/framework-php/frontend/assets/css/mi_estilo.css');
Archivos de ejemplo:

HTML: frontend/pages/main.html
JavaScript: frontend/assets/js/example.js
PHP CRUD: backend/modules/example.php
Solución de problemas comunes
Error al ejecutar composer install:
Asegúrate de que Composer esté instalado (composer --version).
Verifica tu conexión a internet, ya que Composer necesita descargar dependencias.
Si falla, elimina la carpeta vendor/ y el archivo composer.lock, y vuelve a ejecutar composer install.
Error de conexión a la base de datos:
Confirma que el servidor MySQL está activo (en WAMP, el icono debe estar verde).
Verifica que las credenciales en backend/config/.env sean correctas:
text

Copiar
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=framework

Asegúrate de que la base de datos especificada exista.
Página no encontrada:
Si accedes a una página y no se carga, verifica que el archivo exista en frontend/pages/.
Si usas subdirectorios (por ejemplo, frontend/pages/auth/login.html), accede con la URL correspondiente (/auth/login).
Solicitudes AJAX fallan:
Asegúrate de usar window.MyEndpointRoot + "endpoint" en la URL de AJAX.
Verifica que el endpoint esté registrado en backend/config/controller.php.
Contribuciones
Si deseas contribuir al framework, crea un fork del repositorio (si está en GitHub) o contacta al autor. Cualquier mejora o corrección de errores es bienvenida.

Licencia
Este framework se distribuye bajo la licencia MIT. Siéntete libre de usarlo, modificarlo y compartirlo según los términos de esta licencia.

Framework PHP @2025
