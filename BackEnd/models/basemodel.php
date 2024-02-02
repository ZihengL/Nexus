<?php

// require_once "$path/models/dbmanager.php";

function parseColumns($columns = [])
{
    return empty($columns) ? "*" : implode(', ', $columns);
}

function getDataType($column)
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

class BaseModel
{
    protected $pdo;
    public $table;
    public $columns = [];

    protected function __construct($pdo, $table)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->columns = $this->getColumns();
    }

    protected function query($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);

            if (empty($params)) {
                $stmt->execute();
            } else {
                $stmt->execute(array_values($params));
            }

            return $stmt;
        } catch (PDOException $exception) {
            return $exception;
        }
    }

    protected function bindingQuery($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);

            foreach ($params as $param => $value) {
                $stmt->bindValue(":$param", $value, getDataType($value));
            }
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            return $exception;
        }
    }

    // GETTERS/READ

    // Retourne un tableau avec tous les resultats comprenant la valeur.
    public function getAll($column = null, $value = null, $columns = [])
    {
        if (!$column && !$value) {
            $sql = "SELECT " . parseColumns($columns) . " FROM $this->table";
            return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        }

        $sql = "SELECT " . parseColumns($columns) . " FROM $this->table WHERE $column = ?";

        return $this->query($sql, [$value])->fetchAll(PDO::FETCH_ASSOC);
    }

    //  Explicitement retour d'une seule valeur.
    public function getOne($column, $value, $columns = [])
    {
        $sql = "SELECT " . parseColumns($columns) . " FROM $this->table WHERE $column = ?";

        return $this->query($sql, [$value])->fetch(PDO::FETCH_ASSOC);
    }

    public function get($columns = [])
    {
        $stmt = $this->pdo->query("SELECT " . parseColumns($columns) . " FROM $this->table");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id, $columns = [])
    {
        return $this->getOne('id', $id, $columns);
    }

    // OTHER CRUDS

    // $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE devName = ?");
    // $stmt->execute([$devName]);
    // $sql = "INSERT INTO users (first_name, last_name, email) VALUES ('$firstName', '$lastName', '$email')";
    public function create($data)
    {
        $columns = implode(', ', $this->columns);
        $placeholders = substr(str_repeat(",?", count($this->columns)), 1);

        $sql = "INSERT INTO $this->table ($columns) VALUES ($placeholders)";

        if ($this->query($sql, $this->formatData($data))) {
            return true;
        } else {
            return false;
        }
    }

    public function update($id, $data)
    {
        $formattedData = $this->formatData($data);
        $pairs = implode(' = ?, ', array_keys($formattedData)) . ' = ?';
        $formattedData['id'] = $id;

        $sql = "UPDATE $this->table SET $pairs WHERE id = ?";
        echo "<br>UPDATE SQL<br>$sql<br>";

        if ($this->query($sql, $formattedData)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($id)
    {
        $sql = "DELETE FROM $this->table WHERE id = ?";

        return $this->query($sql, [$id]);
    }

    // FILTERS

    public function filterByExactValues($data)
    {
        $validatedData = $this->formatData($data);
        $pairs = implode(' = ?, ', array_keys($validatedData)) . ' = ?';

        $sql = "SELECT * FROM $this->table WHERE $pairs";

        return $this->query($sql, $validatedData)->fetchAll();
    }

    // TOOLS

    public function getColumns($includeID = false)
    {
        $result = $this->query("DESCRIBE $this->table")->fetchAll(PDO::FETCH_COLUMN);

        return $includeID ? $result : array_filter($result, function ($column) {
            return $column !== 'id';
        });
    }

    public function formatData($data)
    {
        $formattedData = [];

        foreach ($this->columns as $column) {
            if (in_array($column, array_keys($data))) {
                $formattedData[$column] = $data[$column];
            }
        }
        return $formattedData;
    }

    public function bindParams($data)
    {
        $params = [];

        foreach ($data as $column => $value) {
            if (in_array($column, $this->columns)) {
                $params[":$column"] = $value;
            }
        }

        return $params;
    }

    public function applyFilters($sql, $filters = [])
    {
        $params = [];
        $validEntries = array_intersect(array_keys($filters), $this->columns);
        $sql .= !empty($validEntries) ? ' WHERE 1 = 1' : '';

        foreach ($validEntries as $column) {
            $sql .= " AND $column = :$column";
            $params[":$column"] = $filters[$column];
        }

        return ['sql' => $sql, 'params' => $params];
    }

    public function applySorting($sql, $sorting = [])
    {
        $validEntries = array_intersect(array_keys($sorting), $this->columns);

        $result = '';
        foreach ($validEntries as $column => $order) {
            $result .= $column . ($order ? 'ASC' : 'DESC') . ', ';
        }

        return $sql . ' ORDER BY ' . $result;
    }

    // public function applySorting($sql, $sorting = [])
    // {
    //     $validEntries = array_intersect(array_keys($sorting), $this->columns);
    
    //     $result = '';
    //     foreach ($validEntries as $column => $order) {
    //         if (!empty($column) && in_array($column, $this->columns)) {
    //             $direction = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC'; // Default to ASC if not DESC
    //             $result .= "$column $direction, ";
    //         }
    //     }
    
    //     $result = rtrim($result, ', '); // Remove the trailing comma and space
    
    //     if (!empty($result)) {
    //         $sql .= ' ORDER BY ' . $result;
    //     }
    
    //     return $sql;
    // }

    public function applyFiltersAndSorting($sql, $filters = [], $sorting = [])
    {
        $filterResult = $this->applyFilters($sql, $filters);
        $sqlWithFilters = $filterResult['sql'];
        $params = $filterResult['params'];

        $sqlWithFiltersAndSorting = $this->applySorting($sqlWithFilters, $sorting);

        echo "<br>Final SQL: " . htmlspecialchars($sqlWithFiltersAndSorting) . "<br>\n";
        echo "<br>Parameters: " . print_r($params) . "</pre>";
        $stmt = $this->pdo->prepare($sqlWithFiltersAndSorting);

        $stmt->execute($params);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


        return $results;
    }
}
