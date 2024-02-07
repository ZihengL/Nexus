<?php

// require_once "$path/models/dbmanager.php";

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
            $sql = "SELECT " . $this->parseColumns($columns) . " FROM $this->table";
            return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        }

        $sql = "SELECT " . $this->parseColumns($columns) . " FROM $this->table WHERE $column = ?";

        return $this->query($sql, [$value])->fetchAll(PDO::FETCH_ASSOC);
    }

    //  Explicitement retour d'une seule valeur.
    public function getOne($column, $value, $columns = [])
    {
        $sql = "SELECT " . $this->parseColumns($columns) . " FROM $this->table WHERE $column = ?";

        return $this->query($sql, [$value])->fetch(PDO::FETCH_ASSOC);
    }

    public function get($columns = [])
    {
        $stmt = $this->pdo->query("SELECT " . $this->parseColumns($columns) . " FROM $this->table");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        return $this->getOne('id', $id, $this->columns);
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

    function parseColumns($columns = [])
    {
        return empty($columns) ? "*" : implode(', ', $columns);
    }

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


    // function getClothesByCriteria($pdo, $minPrice, $maxPrice, $size, $color, $type) {
    //     $sql = "SELECT * FROM clothes WHERE 1 = 1";
    //     $params = [];

    //     // Add price range filter if both minPrice and maxPrice are provided
    //     if ($minPrice !== null && $maxPrice !== null) {
    //         $sql .= " AND price BETWEEN :minPrice AND :maxPrice";
    //         $params[':minPrice'] = $minPrice;
    //         $params[':maxPrice'] = $maxPrice;
    //     }

    //     // Add size filter if provided
    //     if ($size !== null) {
    //         $sql .= " AND size = :size";
    //         $params[':size'] = $size;
    //     }

    //     // Add color filter if provided
    //     if ($color !== null) {
    //         $sql .= " AND color = :color";
    //         $params[':color'] = $color;
    //     }

    //     // Add type filter if provided
    //     if ($type !== null) {
    //         $sql .= " AND type = :type";
    //         $params[':type'] = $type;
    //     }

    //     // Prepare and execute the SQL statement with the parameters
    //     $stmt = $pdo->prepare($sql);
    //     $stmt->execute($params);

    //     // Fetch and return the results
    //     return $stmt->fetchAll();
    // }



    //     $sql = "SELECT * FROM clothes WHERE 1 = 1" .
//        (isset($filters['minPrice'], $filters['maxPrice']) ? " AND price BETWEEN :minPrice AND :maxPrice" : "") .
//        (isset($filters['size']) ? " AND size = :size" : "") .
//        (isset($filters['color']) ? " AND color = :color" : "") .
//        (isset($filters['type']) ? " AND type = :type" : "");

    // $params = array_filter([
//     ':minPrice' => $filters['minPrice'] ?? null,
//     ':maxPrice' => $filters['maxPrice'] ?? null,
//     ':size' => $filters['size'] ?? null,
//     ':color' => $filters['color'] ?? null,
//     ':type' => $filters['type'] ?? null,
// ], function($value) { return !is_null($value); });



    // public function filtrerProduits($type, $couleur, $taille, $minPrice, $maxPrice)
    // {
    //     // echo "Type: " . $type . "<br>" . "Couleur: " .
    //     //  $couleur . "<br>" . "Minimum Price: " .
    //     // $minPrice . "<br>"
    //     // . "Maximum Price: " . $maxPrice . "<br>";

    //     $typePart = $type ? "type = '$type'" : '1'; 
    //     $couleurPart = $couleur ? "FIND_IN_SET('$couleur', couleur) > 0" : '1';
    //     $taillePart = $taille ? "FIND_IN_SET('$taille', taille) > 0" : '1'; 
    //     $minPricePart = $minPrice ? "prix >= $minPrice" : '1';
    //     $maxPricePart = $maxPrice ? "prix <= $maxPrice" : '1';

    //     $sql = "SELECT * FROM produit WHERE $typePart AND $couleurPart AND $taillePart AND $minPricePart AND $maxPricePart";

    //     $result = $this->db->prepare($sql);
    //     $result->execute();

    //     // print_r($result);
    //     return $result->fetchAll(PDO::FETCH_ASSOC);
    // }


    // sql = 'SELECT * FROM users WHERE username = :username AND password = :password';

    public function applyFilters($filters)
    {
        $params = [];
        $validEntries = array_intersect(array_keys($filters), $this->columns);
        // print_r($validEntries);
        // echo "<br>";
        // print_r($this->columns);
        // echo "<br>";
        // print_r($filters);
        // echo "<br> validEntries " . $validEntries . "<br>\n";
        // $sql .= !empty($validEntries) ? ' WHERE 1 = 1' : '';
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE 1 = 1';

        foreach ($validEntries as $column) {
            $sql .= " AND $column = :$column";
            // echo "<br> SQL with columns: " . $sql . "<br>\n";
            $params[":$column"] = $filters[$column];
        }

        return ['sql' => $sql, 'params' => $params];
    }

    public function applySorting($sorting)
    {
        // $sql = "SELECT * FROM users WHERE 1 = 1 AND name = :name AND age = :age ORDER BY $sortBy $sortDirection";
        // ORDER BY name ASC, age DESC

        $validEntries = array_intersect(array_keys($sorting), $this->columns);

        if (empty($validEntries)) {
            return;
            // return $sql; // Return sql by itselfif no valid sorting columns
        }

        // $sortingClauses = [];
        $result = '';
        foreach ($validEntries as $column) {
            $result .= $column . ($sorting[$column] ? ' ASC ' : ' DESC ') . ', ';
        }
        // Remove the last comma 
        $result = rtrim($result, ', ');

        return $result;
        // return $sql . ' ORDER BY ' . $result;
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


    function isColumnSetType($pdo, $tableName, $columnName) {
        try {
            // Prepare the SQL query to get the column type from INFORMATION_SCHEMA.COLUMNS
            $sql = "SELECT COLUMN_TYPE 
                    FROM INFORMATION_SCHEMA.COLUMNS 
                    WHERE TABLE_SCHEMA = :databaseName 
                    AND TABLE_NAME = :tableName 
                    AND COLUMN_NAME = :columnName";
            
            // Assuming $pdo is your PDO database connection
            $stmt = $pdo->prepare($sql);
            
            // Bind the parameters
            $stmt->bindValue(':databaseName', $this->pdo->query('select database()')->fetchColumn());
            $stmt->bindValue(':tableName', $this->table);
            $stmt->bindValue(':columnName', $columnName);
            
            // Execute the query
            $stmt->execute();
            
            // Fetch the column type
            $columnType = $stmt->fetchColumn();
            
            // Check if the column type starts with 'set(' indicating it's a SET type
            if (strpos($columnType, 'set(') === 0) {
                return true;
            }
        } catch (PDOException $e) {
            // Handle potential errors here
            echo "Error: " . $e->getMessage();
        }
        
        return false;
    }

    public function applyFiltersAndSorting($filters, $sorting)
    {
        $filterResults = $this->applyFilters($filters);
        $sortingResults = $this->applySorting($sorting);
        $sqlWithFilters = $filterResults['sql'];
        $params = $filterResults['params'];
        $sqlWithFiltersAndSorting = $sortingResults ? $sqlWithFilters . ' ORDER BY ' . $sortingResults : $sqlWithFilters;


        // {$pdo = new PDO($dsn, $user, $password); // Connect to database
        // $sql = 'SELECT * FROM users WHERE username = :username AND password = :password';
        // $stmt = $pdo->prepare($sql);

        // // Bind parameters
        // $stmt->bindValue(':username', $username);
        // $stmt->bindValue(':password', $hashedPassword);

        // $stmt->execute(); // Execute the prepared statement

        // $results = $stmt->fetchAll(); // Fetch results}

        // echo "<br>Final SQL: " . $sqlWithFiltersAndSorting . "<br>\n";
        // echo "<br>Final SQL: " . htmlspecialchars($sqlWithFiltersAndSorting) . "<br>\n";
        // echo "<br>Parameters: " . print_r($params) . "</pre>";
        // $stmt = $this->pdo->prepare($sqlWithFiltersAndSorting);

        // $stmt->execute($params);

        // $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // return $results;
        // Preparing the SQL statement
        $stmt = $this->pdo->prepare($sqlWithFiltersAndSorting);

        // Dynamically binding parameters
        foreach ($params as $placeholder => $value) {
            $stmt->bindValue($placeholder, $value);
        }

        // Executing the statement
        $stmt->execute();

        // Fetching the results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // $results = $stmt->fetchAll();

        return $results;
    }
}



// function parseColumns($columns = [])
// {
//     return empty($columns) ? "*" : implode(', ', $columns);
// }

// function getDataType($column)
// {
//     switch ($column) {
//         case is_null($column):
//             return PDO::PARAM_NULL;
//         case is_int($column):
//             return PDO::PARAM_INT;
//         case is_bool($column):
//             return PDO::PARAM_BOOL;
//         default:
//             return PDO::PARAM_STR;
//     }
// }
