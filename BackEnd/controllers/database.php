<?php

class DatabaseManager
{
    private static $instance = null;
    private static $host = 'localhost';
    private static $database = 'nexus';
    private static $username = 'pma';
    private static $password = '';

    private $pdo;

    // CONSTRUCTORS

    private function __construct()
    {
        $connectionString = 'mysql:host=' . self::$host . ';dbname=' . self::$database;

        $this->pdo = new PDO($connectionString, self::$username, self::$password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // STATIC

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new DatabaseManager();
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
