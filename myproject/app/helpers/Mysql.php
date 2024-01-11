<?php

namespace Helpers;

use PDO, PDOException;

class Mysql
{
    private static $instance = null;
    private $conn;

    public function __construct()
    {
        $this->create_connection();
    }

    public function create_connection()
    {
        try {
            $this->conn = new PDO('mysql:host=' . $_ENV['MYSQL_HOST'] . ';dbname=' . $_ENV['MYSQL_DATABASE'] . ';charset=UTF8', $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD'], array(
                PDO::ATTR_PERSISTENT => true
            ));
        } catch (PDOException $e) {
            response(false, 'mysql connection error: ' . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Mysql();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function query($sql, $bind = [])
    {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($bind);

        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function update($sql, $bind = [])
    {
        return $this->execute($sql, $bind, 'update');
    }

    public function insert($sql, $bind = [])
    {
        return $this->execute($sql, $bind, 'insert');
    }

    private function execute($sql, $bind = [], $kes='')
    {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($bind);

        return $kes == 'insert' ? $this->conn->lastInsertId() : $stmt->rowCount();
    }
}
