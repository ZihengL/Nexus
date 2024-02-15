<?php

class DatabaseManager
{
    private static $instance = null;

    private const BACKUPS = '/Nexus/BackEnd/remote/backups/';

    private $host;
    private $database;
    private $username;
    private $password;
    private $pdo;

    // CONSTRUCTOR

    private function __construct()
    {
        $this->host = $_ENV['DB_HOST'];
        $this->database = $_ENV['DB_NAME'];
        $this->username = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASS'];

        print($this->username . ' ' . $this->password);

        $connection_string = 'mysql:host=' . $this->host . ';dbname=' . $this->database;
        $this->pdo = new PDO($connection_string, $this->username, $this->password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new DatabaseManager();
        }

        return self::$instance;
    }

    // BACKUP

    public static function getBackupPath()
    {
        return $_SERVER['DOCUMENT_ROOT'] . self::BACKUPS;
    }

    public function backup($filename = null, $max = 5)
    {
        $filename = $filename ?? time();

        $backup_files = array_map('realpath', glob(self::getBackupPath() . '*'));
        usort($backup_files, function ($a, $b) {
            return filemtime($a) <=> filemtime($b);
        });

        $backups_count = count($backup_files);
        if ($backups_count > $max) {
            array_map('unlink', array_slice($backup_files, 0, $backups_count - $max));
        }
    }


    // QUERIES

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

    // GETTERS

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
