<?php

class BaseModel
{
    protected $pdo;
    public $table;
    public $columns = [];
    public $foreign_keys_details = [];

    protected function __construct($pdo, $table, $require_id = false)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->columns = $this->getColumns($require_id);
        $this->foreign_keys_details = $this->getForeignKeyDetails();
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
            // echo "<br>  query : " . print_r($stmt, true) . "<br>";
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Database query error: " . $e->getMessage());
        }
    }

    protected function bindingQuery($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);

            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value);
            }
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database query error: " . $e->getMessage());
        }
    }

    // GETTERS/READ

    public function getAll($column = null, $value = null, $included_columns = [], $sorting = [])
    {
        $sql = "SELECT " . $this->parseColumns($included_columns) . " FROM $this->table";
        // echo "<br>  sorting  getAll : " . print_r($sorting, true) . "<br>";
        $params = [];
        if ($column && $value) {
            $sql .= " WHERE $column = ?";
            $params[] = $value;
        }

        $sortingSql = $this->applySorting($sorting);
        if (!empty($sortingSql)) {
            $sql .= " ORDER BY " . $sortingSql;
        }
        // echo "<br> getAll sql : " . $sql ;
        return $this->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllMatching($filters = [], $sorting = [], $included_columns = [], $joined_tables = [])
    {
        $parsed_columns = $this->parseColumns($included_columns);
        $parsed_joins = $this->parseJoinedTables($joined_tables);

        $sql = "SELECT " . $this->parseColumns($included_columns) . " FROM $this->table WHERE 1 = 1";
        $filterResults = $this->applyFilters($filters);
        $sortingResults = $this->applySorting($sorting);

        $sqlWithFilters = $filterResults['sql'];
        $params = $filterResults['params'];

        $sqlWithFiltersAndSorting = $sortingResults ? $sqlWithFilters . ' ORDER BY ' . $sortingResults : $sqlWithFilters;
        $sqlWithFiltersAndSorting = $sql . $sqlWithFiltersAndSorting;

        return $this->bindingQuery($sqlWithFiltersAndSorting, $params);
    }

    //  Explicitement retour d'une seule valeur.
    public function getOne($column, $value, $included_columns = [])
    {
        $sql = "SELECT " . $this->parseColumns($included_columns) . " FROM $this->table WHERE $column = ?";

        return $this->query($sql, [$value])->fetch(PDO::FETCH_ASSOC);
    }

    // OTHER CRUDS

    public function create($data)
    {
        $data = $this->formatData($data);
        $columns = implode(', ', array_keys($data));
        $placeholders = substr(str_repeat(",?", count($data)), 1);

        // echo "<br> create base_model <br>";
        // print_r($placeholders);

        $sql = "INSERT INTO $this->table ($columns) VALUES ($placeholders)";

        if ($this->query($sql, $data)) {
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

    public function delete($id)
    {
        $sql = "DELETE FROM $this->table WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // TOOLS

    public function getColumns($includeID = false)
    {
        $result = $this->query("DESCRIBE $this->table")->fetchAll(PDO::FETCH_COLUMN);

        return $includeID ? $result : array_filter($result, function ($column) {
            return $column !== 'id';
        });
    }

    public function getForeignKeyDetails()
    {
        $sql = "SELECT TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME 
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = :databaseName 
                AND TABLE_NAME = :tableName 
                AND COLUMN_NAME = :columnName 
                AND REFERENCED_TABLE_NAME IS NOT NULL";

        $foreign_keys = [];
        foreach ($this->columns as $column) {
            $params = [
                ':databaseName' => $_ENV['DB_NAME'],
                ':tableName' => $this->table,
                ':columnName' => $column
            ];

            if ($result = $this->query($sql, $params)->fetch(PDO::FETCH_ASSOC)) {
                $foreign_keys[$column] = $result['REFERENCED_TABLE_NAME'];
            }
        }

        return $foreign_keys;
    }

    public function parseSelectionsToQuery($included_columns = [], $joined_tables = [])
    {
        $local_columns = $this->parseColumns($included_columns);
        $foreign_tables = $this->parseJoinedTables($joined_tables);
    }

    public function parseColumns($included_columns = [])
    {
        return "$this->table." . empty($included_columns) ? '*' : implode(", $this->table.", $included_columns);
    }

    public function parseJoinedTables($joined_tables = [])
    {
        $result = '';

        foreach ($joined_tables as $key => $included_columns) {
            if ($foreign_table = $this->foreign_keys_details[$key]) {
                $subresult = '';
                foreach ($included_columns as $table_column) {
                    $subresult .= ", $foreign_table.$table_column AS $foreign_table" . ucfirst($table_column);
                }

                $result .= "$subresult, FROM $this->table JOIN $foreign_table ON $this->table.$key = $foreign_table.id";
            }
        }

        return $result;
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

    //  FILTERS AND SORTING

    public function applyFilters($filters, $included_columns = [])
    {
        $sql_filters = "";
        $params = [];

        foreach ($filters as $filterKey => $filterValue) {
            if (is_array($filterValue)) { // Corrected check to use $filterValue
                if (isset($filterValue['relatedTable'], $filterValue['values'], $filterValue['wantedColumn'])) {

                    $result = $this->executeRelatedTableFilterAndGetIds($filterKey, $filterValue); // Adjusted to pass current filter info
                    // echo "<br> applyFilters result : $result  <br>\n";
                    // print_r($result);
                    // echo "<br><br>";
                    $sql_filters .= ' AND ' . $result;
                } else {
                    $result = $this->handleRangeCondition($filterKey, $filterValue); // Assuming range conditions are structured as arrays
                    $sql_filters .= ' AND ' . $result['sql'];
                    $params = array_merge($params, $result['params']);
                }
                // echo "<br> applyFilters - sql_filters :  $sql_filters <br>\n";
                // print_r($$result['sql_filters']);
                // echo "<br>";
            } else {
                // Handling for non-array values, assuming direct equality check
                $sql_filters .= " AND $filterKey = :$filterKey";
                $params[":$filterKey"] = $filterValue;
            }
        }

        return ['sql' => $sql_filters, 'params' => $params];
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

    public function applySorting($sorting = null)
    {
        // Check if $sorting is null or not an array
        if (!is_array($sorting)) {
            return ''; // Return an empty string or handle this scenario as appropriate
        }

        $validEntries = array_intersect(array_keys($sorting), $this->getColumns(true));
        // echo "<br> applySorting  validEntries : " . print_r($validEntries, true) . "<br>";
        // echo "<br> applySorting  this->getColumns(true) : " . print_r($this->getColumns(true), true) . "<br>";
        if (empty($validEntries)) {
            return '';
        }

        $result = '';
        foreach ($validEntries as $column) {
            $direction = $sorting[$column] ? 'ASC' : 'DESC';
            $result .= $column . ' ' . $direction . ', ';
        }
        $result = rtrim($result, ', ');
        //  echo "<br> applySorting  result : " . print_r($result, true) . "<br>";

        return $result;
    }
}
