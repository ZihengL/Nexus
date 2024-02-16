<?php

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

    protected function __construct($pdo, $table, $require_id = false)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->columns = $this->getColumns($require_id);
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

    public function getAllMatching($filters = [], $sorting = [], $included_columns = [])
    {
        $result = $this->applyFilters($filters, $included_columns);
        $sql = $result['sql'] . $this->applySorting($sorting);

        return $this->query($sql, $result['params']);
    }

    //  Explicitement retour d'une seule valeur.
    public function getOne($column, $value, $included_columns = [])
    {
        $sql = "SELECT " . $this->parseColumns($included_columns) . " FROM $this->table WHERE $column = ?";

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

        if ($this->query($sql, $formattedData)) {
            return true;
        } else {
            return false;
        }
    }

    /*To update the relational tables 
    $table_obj_ids = [] - is an array because for gameTags there's gameId and tagId 
    but for Reviews there's Id gameId, userId and maybe a third
    */
    public function updateRelationTable($objectToUpdate, $table_obj_ids = [])
    {
        
    }

    // public function updateRelationTable($objectToUpdate, $table_obj_ids = []){  
    // }

    public function delete($id)
    {
        $sql = "DELETE FROM $this->table WHERE id = ?";

        return $this->query($sql, [$id]);
    }

    // FILTERS

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

    public function applyFilters($filters, $included_columns = [])
    {
        $cols = $this->parseColumns($included_columns);
        $sql = 'SELECT ' . $cols . ' FROM ' . $this->table . ' WHERE 1 = 1';
        $params = [];

        foreach ($filters as $filterKey => $filterValue) {
            if (is_array($filterValue)) { // Corrected check to use $filterValue
                if (isset($filterValue['relatedTable'], $filterValue['values'], $filterValue['wantedColumn'])) {
                    $result = $this->executeRelatedTableFilterAndGetIds($filterKey, $filterValue); // Adjusted to pass current filter info
                    $sql .= ' AND ' . $result;
                } else {
                    $result = $this->handleRangeCondition($filterKey, $filterValue); // Assuming range conditions are structured as arrays
                    $sql .= ' AND ' . $result['sql'];
                    $params = array_merge($params, $result['params']);
                }
            } else {
                $sql .= " AND $filterKey = :$filterKey";
                $params[":$filterKey"] = $filterValue;
            }
        }

        return ['sql' => $sql, 'params' => $params];
    }

    function handleRangeCondition($column, $conditions)
    {
        $sqlParts = [];
        $params = [];
        foreach ($conditions as $condition => $value) {
            switch ($condition) {
                case 'gt':
                    $sqlParts[] = "$column > :{$column}_gt";
                    $params[":{$column}_gt"] = $value;
                    break;
                case 'lt':
                    $sqlParts[] = "$column < :{$column}_lt";
                    $params[":{$column}_lt"] = $value;
                    break;
                case 'gte':
                    $sqlParts[] = "$column >= :{$column}_gte";
                    $params[":{$column}_gte"] = $value;
                    break;
                case 'lte':
                    $sqlParts[] = "$column <= :{$column}_lte";
                    $params[":{$column}_lte"] = $value;
                    break;
                case 'contain':
                    $sqlParts[] = "$column LIKE :{$column}_contain";
                    $params[":{$column}_contain"] = "%" . $value . "%";
                    break;
            }
        }

        return ['sql' => implode(' AND ', $sqlParts), 'params' => $params];
    }

    protected function executeRelatedTableFilterAndGetIds($filterKey, $filterValue)
    {
        $relatedTable = $filterValue['relatedTable'];
        $filterValues = $filterValue['values'];
        $wantedColumn = $filterValue['wantedColumn'];

        // Your existing SQL construction
        $placeholders = implode(', ', array_fill(0, count($filterValues), '?'));
        $sql = "SELECT {$wantedColumn} FROM {$relatedTable}  WHERE {$filterKey} IN ($placeholders)";

        // Prepare and execute the query
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($filterValues);
        $ids = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

        // Build the 'IN' clause for the main query using fetched IDs
        if (!empty($ids)) {
            // Sanitize IDs if necessary to ensure they're safe for inclusion
            $inList = implode(', ', array_map(function ($id) {
                return intval($id);
            }, $ids)); // Ensuring IDs are integers
            return " {$this->table}.id IN ($inList)";
        }

        return '';
    }

    public function applySorting($sorting)
    {
        // Check if $sorting is null or not an array
        if (!is_array($sorting)) {
            return ''; // Return an empty string or handle this scenario as appropriate
        }

        $validEntries = array_intersect(array_keys($sorting), $this->columns);

        if (empty($validEntries)) {
            return ''; // Return an empty string if no valid sorting columns are provided
        }

        $result = '';
        foreach ($validEntries as $column) {
            $direction = $sorting[$column] ? 'ASC' : 'DESC';
            $result .= $column . ' ' . $direction . ', ';
        }
        $result = rtrim($result, ', ');

        return $result; // This will be appended to the SQL query if not empty
    }

    public function applyFiltersAndSorting($filters, $sorting)
    {
        $filterResults = $this->applyFilters($filters);
        $sortingResults = $this->applySorting($sorting);
        $sqlWithFilters = $filterResults['sql'];
        $params = $filterResults['params'];
        $sqlWithFiltersAndSorting = $sortingResults ? $sqlWithFilters . ' ORDER BY ' . $sortingResults : $sqlWithFilters;

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

    // TOOLS

    function parseColumns($columns = [])
    {
        return empty($columns) ? "*" : implode(', ', $columns);
    }
}
