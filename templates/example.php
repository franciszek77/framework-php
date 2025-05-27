<?php
namespace MyFramework\Modules;

use MyFramework\Core\Database;

class Example {
    private $db;

    public function __construct($db = null) {
        $this->db = $db ?? new Database(\MyFramework\Core\Config::$db_config);
    }

    public function create($username, $password) {
        $data = ["username" => $username, "password" => password_hash($password, PASSWORD_DEFAULT)];
        return $this->db->insert("users", $data);
    }

    public function readAll() {
        $sql = "SELECT * FROM users";
        return $this->db->get_results($sql);
    }

    public function readOne($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        return $this->db->get_row($sql, ["id" => $id]);
    }

    public function update($id, $username, $password) {
        $data = ["username" => $username, "password" => password_hash($password, PASSWORD_DEFAULT)];
        $where = ["id" => $id];
        return $this->db->update("users", $data, $where);
    }

    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->query($sql, ["id" => $id]);
        return $stmt->rowCount();
    }

    public static function handle($method, $db) {
        $example = new self($db);

        header("Content-Type: application/json");

        switch ($method) {
            case "POST":
                $data = json_decode(file_get_contents("php://input"), true);
                if (isset($data["username"], $data["password"])) {
                    $result = $example->create($data["username"], $data["password"]);
                    return ["success" => true, "id" => $result];
                } else {
                    return ["success" => false, "message" => "Faltan datos"];
                }
                break;

            case "GET":
                if (isset($_GET["id"])) {
                    $result = $example->readOne($_GET["id"]);
                } else {
                    $result = $example->readAll();
                }
                return ["success" => true, "data" => $result];
                break;

            case "PUT":
                $data = json_decode(file_get_contents("php://input"), true);
                if (isset($data["id"], $data["username"], $data["password"])) {
                    $result = $example->update($data["id"], $data["username"], $data["password"]);
                    return ["success" => true, "affected" => $result];
                } else {
                    return ["success" => false, "message" => "Faltan datos"];
                }
                break;

            case "DELETE":
                $data = json_decode(file_get_contents("php://input"), true);
                if (isset($data["id"])) {
                    $result = $example->delete($data["id"]);
                    return ["success" => true, "affected" => $result];
                } else {
                    return ["success" => false, "message" => "Falta el ID"];
                }
                break;

            default:
                return ["success" => false, "message" => "Método no soportado"];
                break;
        }
    }
}