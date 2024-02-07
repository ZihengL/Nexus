<?php

class DatabaseManager
{
    private static $instance = null;

    private $host = 'localhost';
    private $database = 'nexus';
    private $username = 'nexus';
    private $password = '123';
    private $pdo;

    // CONSTRUCTORS

    private function __construct($host, $database, $username, $password)
    {
        $this->host = $host;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;

        $connection_string = 'mysql:host=' . $this->host . ';dbname=' . $this->database;
        $this->pdo = new PDO($connection_string, $this->username, $this->password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // STATIC

    public static function getInstance($host, $database, $username, $password)
    {
        if (self::$instance == null) {
            self::$instance = new DatabaseManager($host, $database, $username, $password);
        }

        return self::$instance;
    }

    // PRIVATE

    public function query($sql)
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            return $stmt;
        } catch (PDOException $exception) {
            return $exception;
        }
    }

    public function bindingQuery($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);

            foreach ($params as $param => $value) {
                $stmt->bindValue(":$param", $value, $this->getDataType($value));
            }
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            return $exception;
        }
    }

    // PUBLIC

    public function getPDO()
    {
        return $this->pdo;
    }

    public function getDataType($column)
    {
        switch ($column) {
            case is_null($column):
                return PDO::PARAM_NULL;
            case is_int($column):
                return PDO::PARAM_INT;
            case is_bool($column):
                return PDO::PARAM_BOOL;
            default:
                return PDO::PARAM_STR;
        }
    }

    public function getTables()
    {
        $result = $this->query("SHOW TABLES");

        return $result->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getColumnsFromTable($table)
    {
        $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = :tableName";

        return $this->bindingQuery($sql, ['tableName' => $table]);
    }
}
