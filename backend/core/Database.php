<?php
namespace MyFramework\Core;

use PDO;
use PDOException;

class Database {
    private $conn;
    private $last_query;

    public function __construct($config) {
        try {
            $dsn = "mysql:host={$config['host']};dbname={$config['name']};charset=utf8mb4";
            $this->conn = new PDO($dsn, $config['user'], $config['pass']);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die(json_encode(['success' => false, 'message' => 'Error de conexión: ' . $e->getMessage()]));
        }
    }

    public function query($sql, $params = []) {
        $this->last_query = $sql;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function get_results($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    public function get_row($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }

    public function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $this->query($sql, $data);
        return $this->conn->lastInsertId();
    }

    public function update($table, $data, $where) {
        $set = '';
        $params = [];
        foreach ($data as $key => $value) {
            $set .= "$key = :$key, ";
            $params[$key] = $value;
        }
        $set = rtrim($set, ', ');

        $where_clause = '';
        foreach ($where as $key => $value) {
            $where_clause .= "$key = :where_$key AND ";
            $params["where_$key"] = $value;
        }
        $where_clause = rtrim($where_clause, ' AND ');

        $sql = "UPDATE $table SET $set WHERE $where_clause";
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    public function last_query() {
        return $this->last_query;
    }
}