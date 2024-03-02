<?php

class BaseModel
{
    public static $print_queries = true;

    protected $pdo;
    public $table;
    public $columns = [];
    protected $keys = [];

    protected function __construct($pdo, $table, $require_id = false)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->columns = $this->getColumns($require_id);
        $this->addDeconstructedKeys([...$this->getKeysDetails(false), $this->getKeysDetails(true)]);
    }

    private function addDeconstructedKeys($keys)
    {
        if (!empty($keys) && isset($keys['EXT_TAB']))
            $this->keys[array_pop($keys)] = $keys;
        else
            foreach ($keys as $subkeys)
                $this->addDeconstructedKeys($subkeys);
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

            if (self::$print_queries) {
                echo "<h5>{$this->table}</h5><br>";
                printall($stmt);
            }

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

            if (self::$print_queries) {
                echo "<h5>{$this->table}</h5><br>";
                printall($stmt);
            }

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
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

    public function getOne($column, $value, $included_columns = [], $joined_tables = [])
    {
        $sql = $this->buildSelectionLayer($included_columns, $joined_tables) . " WHERE $this->table.$column = ?";

        return $this->query($sql, [$value])->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll($column = null, $value = null, $included_columns = [], $sorting = [], $joined_tables = [])
    {
        $sql = $this->buildSelectionLayer($included_columns, $joined_tables);
        $params = [];

        if ($column && $value) {
            $sql .= " WHERE $this->table.$column = ?";
            $params = [$value];
        }

        $sort_layer = $this->applySorting($sorting);
        $sql .= !empty($sort_layer) ? " ORDER BY $sort_layer" : '';

        return $this->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllMatching($filters = [], $sorting = [], $included_columns = [], $joined_tables = [])
    {
        $sql = $this->buildSelectionLayer($included_columns, $joined_tables) . ' WHERE 1 = 1';

        $filterResults = $this->applyFilters($filters);
        $sortingResults = $this->applySorting($sorting);

        $sqlWithFilters = $filterResults['sql'];
        $params = $filterResults['params'];

        $sqlWithFiltersAndSorting = $sql . $sortingResults ? "$sqlWithFilters ORDER BY $sortingResults" : $sqlWithFilters;

        return $this->bindingQuery($sqlWithFiltersAndSorting, $params);
    }

    /*******************************************************************/
    /****************************** CRUDS ******************************/
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

        return $this->query($sql, $formattedData)->fetch(); // Bugs?
    }

    public function delete($id)
    {
        return $this->query("DELETE FROM $this->table WHERE id = $id")->fetch();
        // $stmt = $this->pdo->prepare($sql);

        // $stmt->bindParam(1, $id, PDO::PARAM_INT);

        // return $stmt->execute();
    }

    public function formatData($data)
    {
        $formatted = [];

        foreach ($this->columns as $column)
            if (in_array($column, array_keys($data)))
                $formatted[$column] = $data[$column];

        return $formatted;
    }

    /*******************************************************************/
    /************************* SELECTION LAYER *************************/
    /*******************************************************************/

    public function getColumns($includeID = false)
    {
        $result = $this->query("DESCRIBE {$this->table}")->fetchAll(PDO::FETCH_COLUMN);

        return $includeID ? $result : array_filter($result, function ($column) {
            return $column !== 'id';
        });
    }

    public function getKeysDetails($is_internal_keys = true)
    {
        $int_col = "COLUMN_NAME AS 'INT_COL'";
        $ext_col = "COLUMN_NAME AS 'EXT_COL'";
        $ext_tab = "TABLE_NAME AS 'EXT_TAB'";

        $table_condition = "TABLE_NAME = '{$this->table}'";

        if ($is_internal_keys) {
            $ext_col = "REFERENCED_$ext_col";
            $ext_tab = "REFERENCED_$ext_tab";

            $table_condition = "$table_condition AND REFERENCED_TABLE_NAME IS NOT NULL";
        } else {
            $int_col = "REFERENCED_$int_col";

            $table_condition = "REFERENCED_$table_condition";
        }

        $sql = "SELECT $int_col, $ext_col, $ext_tab 
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = '{$_ENV['DB_NAME']}' 
                AND $table_condition";

        $keys_details = $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        foreach ($keys_details as $key => $values) {
            // TODO: CHECK IF RELATIONSHIP MULTIPLICITY CAN BE COMPUTED IN THIS QUERY - SEE PARSEJOINEDTABLES
        }

        return $keys_details;
    }

    public function getAdjacentKeysDetails($table)
    {
        $sql = "SELECT
                
        ";
    }

    public function buildSelectionLayer($included_columns = [], $join_keys = [])
    {
        $columns = $this->parseColumns($included_columns);
        $joins = $this->parseJoinedTables($join_keys);

        return "SELECT $columns {$joins['selects']} FROM {$this->table} {$joins['joins']}";
    }

    public function parseColumns($included_columns = [])
    {
        // return "{$this->table}." . empty($included_columns) ? '*' : implode(", {$this->table}.", $included_columns);
        return $this->table . '.' . implode(", {$this->table}.", $included_columns);
    }

    public function parseJoinedTables($joined_tables = [])
    {
        $join_layer = [];
        $join_layer['selects'] = '';
        $join_layer['joins'] = '';

        foreach ($joined_tables as $ref_tab => $included_columns)
            if ($keyset = $this->keys[$ref_tab]) {
                ['INT_COL' => $int_col, 'EXT_COL' => $ext_col] = $keyset;

                foreach ($included_columns as $join_column)
                    $join_layer['selects'] .= ", {$ref_tab}.{$join_column} AS {$ref_tab}_{$join_column}";

                // TODO: IF MULTIPLICITY CAN BE COMPUTED, CHANGE THE JOIN TYPE HERE
                $join_layer['joins'] .= " FULL JOIN $ref_tab ON {$this->table}.{$int_col} = {$ref_tab}.{$ext_col}";
            }

        return $join_layer;
    }

    /*******************************************************************/
    /************************** SIEVING LAYER **************************/
    /*******************************************************************/

    public function applyFilters($filters, $included_columns = [])
    {
        $sql_filters = "";
        $params = [];

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                if (isset($value['relatedTable'], $value['values'], $value['wantedColumn'])) {
                    // Adjusted to pass current filter info
                    $result = $this->executeRelatedTableFilterAndGetIds($key, $value);
                    $sql_filters .= ' AND ' . $result;
                } else {
                    // Assuming range conditions are structured as arrays
                    $result = $this->handleRangeCondition($key, $value);
                    $sql_filters .= ' AND ' . $result['sql'];
                    $params = array_merge($params, $result['params']);
                }
            } else {
                $sql_filters .= " AND $key = :$key";
                $params[":$key"] = $value;
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
        $sql = "SELECT {$wantedColumn} 
                FROM {$relatedTable}  
                WHERE {$filterKey} 
                IN ($placeholders)";

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
