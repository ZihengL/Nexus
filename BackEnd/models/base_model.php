<?php

class BaseModel
{
    protected $pdo;
    public $table;
    public $columns = [];
    public $keys = [];

    protected function __construct($pdo, $table, $require_id = false)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->columns = $this->getColumns($require_id);

        $this->keys['INT'] = $this->getInternalKeysDetails();
        $this->keys['EXT'] = $this->getExternalKeysDetails();

        echo $this->table . '<br>';
        echo '<pre>' . print_r($this->keys['internal']) . '</pre>';
        echo '<pre>' . print_r($this->keys['external']) . '</pre>';
    }

    /*******************************************************************/
    /****************************** QUERY ******************************/
    /*******************************************************************/

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
        } catch (PDOException $e) {
            echo '<br>err<br>' . $sql . '<br>err<br>';

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
            echo '<br>err<br>' . $sql . '<br>err<br>';

            throw new Exception("Database query error: " . $e->getMessage());
        }
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

    /*******************************************************************/
    /***************************** GETTERS *****************************/
    /*******************************************************************/

    public function getAll($column = null, $value = null, $included_columns = [], $sorting = [])
    {
        $sql = "SELECT " . $this->parseColumns($included_columns) . " FROM $this->table";

        $params = [];
        if ($column && $value) {
            $sql .= " WHERE $column = ?";
            $params[] = $value;
        }

        $sortingSql = $this->applySorting($sorting);
        if (!empty($sortingSql)) {
            $sql .= " ORDER BY " . $sortingSql;
        }

        return $this->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }


    // TODO: ASKJDHSAKJFHASKLFHADSLFHALFHSAKLDHASLHLFHASKL ADAPT IT TO HANDLE INTERNAL AND EXTERNAL KEYS
    public function getAllMatching($filters = [], $sorting = [], $included_columns = [], $join_keys = [])
    {
        $selection_layer = $this->buildSelectionLayer($included_columns, $join_keys);
        $sql = $selection_layer . ' WHERE 1 = 1';

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

    /*******************************************************************/
    /*************************** OTHER CRUDS ***************************/
    /*******************************************************************/

    public function create($data)
    {
        $data = $this->formatData($data);
        $columns = implode(', ', array_keys($data));
        $placeholders = substr(str_repeat(", ?", count($data)), 1);

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

    /*******************************************************************/
    /************************* SELECTION LAYER *************************/
    /*******************************************************************/

    public function getColumns($includeID = false)
    {
        $result = $this->query("DESCRIBE $this->table")->fetchAll(PDO::FETCH_COLUMN);

        return $includeID ? $result : array_filter($result, function ($column) {
            return $column !== 'id';
        });
    }

    public function getInternalKeysDetails()
    {
        $sql = "SELECT 
                    COLUMN_NAME AS 'INT_COL', 
                    REFERENCED_TABLE_NAME AS 'EXT_TABLE', 
                    REFERENCED_COLUMN_NAME AS 'EXT_COL' 
                FROM 
                    INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE 
                    TABLE_SCHEMA = :databaseName
                AND TABLE_NAME = :tableName
                AND REFERENCED_TABLE_NAME IS NOT NULL";

        $params = [
            ':databaseName' => $_ENV['DB_NAME'],
            ':tableName' => $this->table
        ];

        return $this->bindingQuery($sql, $params);
    }

    public function getExternalKeysDetails()
    {
        $sql = "SELECT 
                    REFERENCED_COLUMN_NAME AS INT_COL,
                    TABLE_NAME AS EXT_TABLE,
                    COLUMN_NAME AS EXT_COL
                FROM
                    INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE
                    REFERENCED_TABLE_SCHEMA = '{$_ENV['DB_NAME']}' 
                AND
                    REFERENCED_TABLE_NAME = '$this->table'";

        return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Note: Assumes that only one datapoint/row is linked per foreign joined table
    public function buildSelectionLayer($included_columns = [], $join_keys = [])
    {
        $result = 'SELECT ' . $this->parseColumns($included_columns);

        if ($foreign_selects = $this->parseJoinedTables($join_keys)) {
            $selects = $foreign_selects['selects'];
            $joins = $foreign_selects['joins'];

            $result .= " $selects FROM $this->table $joins";
        }

        return $result;
    }

    public function parseColumns($included_columns = [])
    {
        return "$this->table." . empty($included_columns) ? '*' : implode(", $this->table.", $included_columns);
    }

    public function parseJoinedTables($join_keys = [])
    {
        $selects = '';
        $joins = '';

        foreach ($this->keys as [
            'INT_COL' => $int_col,
            'EXT_TABLE' => $ext_table,
            'EXT_COL' => $ext_col
        ]) {
            if ($included_columns = $join_keys[$int_col]) {

                foreach ($included_columns as $included_column) {
                    $selects .= ", $ext_table.$included_column AS {$ext_table}_{$included_column}";
                }

                $joins .= " JOIN $ext_table ON $this->table.$int_col = $ext_table.$ext_col";
            }

            return ['selects' => $selects, 'joins' => $joins];
        }

        return null;
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

    /*******************************************************************/
    /********************* FILTERS & SORTING LAYER *********************/
    /*******************************************************************/

    public function applyFilters($filters, $included_columns = [])
    {
        $sql_filters = "";
        $params = [];

        foreach ($filters as $filterKey => $filterValue) {
            if (is_array($filterValue)) { // Corrected check to use $filterValue
                if (isset($filterValue['relatedTable'], $filterValue['values'], $filterValue['wantedColumn'])) {

                    $result = $this->executeRelatedTableFilterAndGetIds($filterKey, $filterValue); // Adjusted to pass current filter info
                    $sql_filters .= ' AND ' . $result;
                } else {
                    $result = $this->handleRangeCondition($filterKey, $filterValue); // Assuming range conditions are structured as arrays
                    $sql_filters .= ' AND ' . $result['sql'];
                    $params = array_merge($params, $result['params']);
                }
            } else {
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
        if (empty($validEntries)) {
            return '';
        }

        $result = '';
        foreach ($validEntries as $column) {
            $direction = $sorting[$column] ? 'ASC' : 'DESC';
            $result .= $column . ' ' . $direction . ', ';
        }
        $result = rtrim($result, ', ');

        return $result;
    }
}
