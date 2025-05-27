# Framework PHP

## Introducción

Este framework proporciona una estructura básica para desarrollar aplicaciones web en PHP con soporte para operaciones CRUD y una API RESTful. Está diseñado para ser ligero y fácil de usar, permitiendo a los programadores crear páginas y módulos rápidamente con solo tres archivos: un HTML, un JavaScript/jQuery, y un PHP para manejar la lógica del backend. A continuación, se explica cómo instalar el framework, crear módulos, configurar páginas y endpoints, y más.

---

## Instalación

### Pasos para instalar

1. Descomprime el archivo `.rar` en tu directorio de servidor (por ejemplo, `C:\wamp64\www\framework-php`).
2. Asegúrate de tener Composer instalado (`composer --version`).
3. Abre una terminal en el directorio raíz del proyecto y ejecuta:
4. Crea una base de datos MySQL (por ejemplo, `framework`) y anota su nombre.
5. Accede a `http://localhost/framework-php/install.php` desde tu navegador, ingresa el nombre de la base de datos (y, si aplica, el usuario y contraseña de MySQL), y guarda.
6. Serás redirigido a `http://localhost/framework-php/public/main`. ¡Listo para programar!

### Requisitos

- PHP con soporte para MySQLi.
- Servidor web (como WAMP o XAMPP).
- Composer instalado.

**Tiempo estimado**: Menos de 2 minutos.

---

## Estructura de directorios

Después de ejecutar `install.php`, el framework tendrá la siguiente estructura:

| Directorio   | Descripción                                         |
| ------------ | --------------------------------------------------- |
| `backend/`   | Contiene la lógica del servidor (módulos, config)   |
| `frontend/`  | Contiene páginas HTML y assets (JS, CSS)            |
| `public/`    | Contiene los puntos de entrada (API, index)         |
| `templates/` | Plantillas iniciales generadas por `install.php`    |
| `vendor/`    | Dependencias de Composer (generado automáticamente) |

---

## Creación de un módulo PHP

Los módulos PHP se colocan en el directorio `backend/modules/` y deben usar el namespace `MyFramework\Modules`. A continuación, un ejemplo de un módulo CRUD básico.

### Ejemplo: `example.php`

Crea el archivo `backend/modules/example.php` con el siguiente contenido:

```php

<?php
namespace MyFramework\Modules;

use MyFramework\Core\Database;

class Example {
    private $db;

    public function __construct() {
        $this->db = new Database(\MyFramework\Core\Config::$db_config);
    }

    // Registrar
    public function create($username, $password) {
        $data = [
            "username" => $username,
            "password" => password_hash($password, PASSWORD_DEFAULT)
        ];
        return $this->db->insert("users", $data);
    }

    // Consultar todos
    public function readAll() {
        $sql = "SELECT * FROM users";
        return $this->db->get_results($sql);
    }

    // Consultar uno
    public function readOne($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        return $this->db->get_row($sql, ["id" => $id]);
    }

    // Modificar
    public function update($id, $username, $password) {
        $data = [
            "username" => $username,
            "password" => password_hash($password, PASSWORD_DEFAULT)
        ];
        $where = ["id" => $id];
        return $this->db->update("users", $data, $where);
    }

    // Eliminar
    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->query($sql, ["id" => $id]);
        return $stmt->rowCount();
    }

    // Manejar solicitudes API
    public static function handle() {
        $method = $_SERVER["REQUEST_METHOD"];
        $example = new self();

        header("Content-Type: application/json");

        switch ($method) {
            case "POST":
                $data = json_decode(file_get_contents("php://input"), true);
                if (isset($data["username"], $data["password"])) {
                    $result = $example->create($data["username"], $data["password"]);
                    echo json_encode(["success" => true, "id" => $result]);
                } else {
                    echo json_encode(["success" => false, "message" => "Faltan datos"]);
                }
                break;

            case "GET":
                if (isset($_GET["id"])) {
                    $result = $example->readOne($_GET["id"]);
                } else {
                    $result = $example->readAll();
                }
                echo json_encode(["success" => true, "data" => $result]);
                break;

            case "PUT":
                $data = json_decode(file_get_contents("php://input"), true);
                if (isset($data["id"], $data["username"], $data["password"])) {
                    $result = $example->update($data["id"], $data["username"], $data["password"]);
                    echo json_encode(["success" => true, "affected" => $result]);
                } else {
                    echo json_encode(["success" => false, "message" => "Faltan datos"]);
                }
                break;

            case "DELETE":
                $data = json_decode(file_get_contents("php://input"), true);
                if (isset($data["id"])) {
                    $result = $example->delete($data["id"]);
                    echo json_encode(["success" => true, "affected" => $result]);
                } else {
                    echo json_encode(["success" => false, "message" => "Falta el ID"]);
                }
                break;

            default:
                echo json_encode(["success" => false, "message" => "Método no soportado"]);
                break;
        }
    }
}

```

Nota: Este archivo (example.php) es un ejemplo de referencia para operaciones CRUD. No lo elimines, ya que te servirá como guía para crear nuevos módulos.

## Creación y acceso a páginas HTML
Crear una página HTML

Las páginas HTML se colocan en el directorio frontend/pages/. 
Puedes crear tu archivo HTML con el diseño que prefieras (inputs, tablas, etc.) y usar las clases de Bootstrap para aplicar estilos.

Ejemplo: Crea frontend/pages/mi_pagina.html:

```html

<div class="container">
    <h2>Mi Página</h2>
    <p>Este es un ejemplo de página personalizada usando Bootstrap.</p>
    <button class="btn btn-primary">Botón de ejemplo</button>
</div>

```

## Organización con subdirectorios:

Puedes crear subdirectorios dentro de frontend/pages/ para organizar tus archivos. 
Por ejemplo, frontend/pages/auth/mi_pagina.html. El framework los reconocerá automáticamente.

Acceder a las páginas HTML

La URL principal es http://localhost/framework-php/public/, que muestra frontend/pages/main.html por defecto.
Para acceder a una página específica, usa la estructura /<ruta>:
Ejemplo: http://localhost/framework-php/public/mi_pagina 
carga frontend/pages/mi_pagina.html. Si usas subdirectorios: http://localhost/framework-php/public/auth/mi_pagina carga frontend/pages/auth/mi_pagina.html.
Nota: No incluyas la extensión .html en la URL.

## Creación de un script JavaScript

Los scripts JavaScript/jQuery se colocan en el directorio frontend/assets/js/. A continuación, un ejemplo de cómo interactuar con el endpoint /framework-php/public/api/example.

Ejemplo: example.js
Crea el archivo frontend/assets/js/example.js con el siguiente contenido:

```javascript

jQuery(document).ready(function($) {
// Ejemplo de CRUD con AJAX para el endpoint /framework-php/public/api/example

    // Registrar un usuario
    function createUser(username, password) {
        $.ajax({
            url: window.MyEndpointRoot + "example",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ username: username, password: password }),
            success: function(response) {
                console.log("Usuario creado:", response);
            },
            error: function(xhr) {
                console.error("Error al crear usuario:", xhr.responseText);
            }
        });
    }

    // Consultar todos los usuarios
    function readAllUsers() {
        $.ajax({
            url: window.MyEndpointRoot + "example",
            type: "GET",
            success: function(response) {
                console.log("Usuarios:", response.data);
            },
            error: function(xhr) {
                console.error("Error al consultar usuarios:", xhr.responseText);
            }
        });
    }

    // Consultar un usuario por ID
    function readUser(id) {
        $.ajax({
            url: window.MyEndpointRoot + "example?id=" + id,
            type: "GET",
            success: function(response) {
                console.log("Usuario:", response.data);
            },
            error: function(xhr) {
                console.error("Error al consultar usuario:", xhr.responseText);
            }
        });
    }

    // Modificar un usuario
    function updateUser(id, username, password) {
        $.ajax({
            url: window.MyEndpointRoot + "example",
            type: "PUT",
            contentType: "application/json",
            data: JSON.stringify({ id: id, username: username, password: password }),
            success: function(response) {
                console.log("Usuario actualizado:", response);
            },
            error: function(xhr) {
                console.error("Error al actualizar usuario:", xhr.responseText);
            }
        });
    }

    // Eliminar un usuario
    function deleteUser(id) {
        $.ajax({
            url: window.MyEndpointRoot + "example",
            type: "DELETE",
            contentType: "application/json",
            data: JSON.stringify({ id: id }),
            success: function(response) {
                console.log("Usuario eliminado:", response);
            },
            error: function(xhr) {
                console.error("Error al eliminar usuario:", xhr.responseText);
            }
        });
    }

    // Ejemplo de uso (descomentar para probar)
    /*
    createUser("testuser", "testpass");
    readAllUsers();
    readUser(1);
    updateUser(1, "newuser", "newpass");
    deleteUser(1);
    */

});

```

Nota: Siempre usa window.MyEndpointRoot como prefijo en las URLs de AJAX. Esto asegura que las solicitudes se dirijan correctamente al backend.

Encolar scripts y estilos
Puedes encolar scripts y estilos personalizados editando backend/core/Enqueue.php.

Ejemplo de encolado
Encolar un script:

```php

self::enqueue_script("mi_script", "/framework-php/frontend/assets/js/mi_script.js", ["jquery"], true);
Encolar un estilo:
php

self::enqueue_style("mi_estilo", "/framework-php/frontend/assets/css/mi_estilo.css");
Nota sobre Bootstrap:

Bootstrap está encolado por defecto (CSS y JS). Si no lo vas a usar, comenta o elimina las siguientes líneas en Enqueue.php:
php


self::enqueue_style('bootstrap', '/framework-php/frontend/assets/bootstrap/css/bootstrap.min.css');
self::enqueue_script('bootstrap', '/framework-php/frontend/assets/bootstrap/js/bootstrap.bundle.min.js');

```
## Configurar el manejador de endpoints

Edita backend/config/controller.php para agregar nuevos endpoints a tu API.

Ejemplo: controller.php
```php

<?php
return [
    'modules' => [
        'login' => 'Login',
        'usuario' => 'Usuario',
        'example' => 'Example',
    ],
    'api_base' => '/framework-php/public/api/',
];

```

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
```php

self::enqueue_style('mi_estilo', '/framework-php/frontend/assets/css/mi_estilo.css');

```
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
