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
        $this->keys = [...$this->getKeysDetails(true), $this->getKeysDetails(false)];

        echo $this->table . '<br>';
        echo '<pre>' . print_r($this->keys) . '</pre>';
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

    public function getOne($column, $value, $included_columns = [], $joined_tables = [])
    {
        $sql = $this->buildSelectionLayer($included_columns, $joined_tables) . "WHERE $column = ?";

        return $this->query($sql, [$value])->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll($column = null, $value = null, $included_columns = [], $sorting = [], $joined_tables = [])
    {
        $sql = $this->buildSelectionLayer($included_columns, $joined_tables) . $column ?? " WHERE $column = ?";

        $sort_layer = $this->applySorting($sorting);
        $sql .= !empty($sort_layer) ? " ORDER BY " . $sort_layer : '';

        return $this->query($sql, $value ?? [$value])->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllMatching($filters = [], $sorting = [], $included_columns = [], $joined_tables = [])
    {
        $sql = $this->buildSelectionLayer($included_columns, $joined_tables) . ' WHERE 1 = 1';

        $filterResults = $this->applyFilters($filters);
        $sortingResults = $this->applySorting($sorting);

        $sqlWithFilters = $filterResults['sql'];
        $params = $filterResults['params'];

        $sqlWithFiltersAndSorting = $sql . $sortingResults ? "$sqlWithFilters ORDER BY $sortingResults" : $sqlWithFilters;
        // $sqlWithFiltersAndSorting = $sql . $sqlWithFiltersAndSorting;

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
        $first_member = "COLUMN_NAME AS 'INT_COL'";
        $second_member = "TABLE_NAME AS 'EXT_TAB'";
        $third_member = "COLUMN_NAME AS 'EXT_COL'";

        $table_condition = "TABLE_NAME = '{$this->table}'";

        if ($is_internal_keys) {
            $second_member = "REFERENCED_$second_member";
            $third_member = "REFERENCED_$third_member";

            $table_condition = "$table_condition AND REFERENCED_TABLE_NAME IS NOT NULL";
        } else {
            $first_member = "REFERENCED_$first_member";

            $table_condition = "REFERENCED_$table_condition";
        }

        $sql = "SELECT $first_member, $second_member, $third_member 
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = '{$_ENV['DB_NAME']}' 
                AND $table_condition";

        return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buildSelectionLayer($included_columns = [], $join_keys = [])
    {
        $selections = "SELECT {$this->parseColumns($included_columns)}";
        $join_layer = $this->parseJoinedTables($join_keys);

        if (!empty($join_layer['selects'] && !empty($join_layer['joins'])))
            $selections .= " {$join_layer['selects']} FROM $this->table {$join_layer['joins']}";

        return $selections;
    }

    public function parseColumns($included_columns = [])
    {
        return "$this->table." . empty($included_columns) ? '*' : implode(", $this->table.", $included_columns);
    }

    public function parseJoinedTables($join_keys = [])
    {
        $join_layer = [];
        $join_layer['selects'] = '';
        $join_layer['joins'] = '';

        foreach ($this->keys as ['INT_COL' => $int_col, 'EXT_TAB' => $ext_tab, 'EXT_COL' => $ext_col])
            if ($included_columns = $join_keys[$ext_tab]) {
                foreach ($included_columns as $included_column)
                    $join_layer['selects'] .= ", {$ext_tab}.{$included_column} AS {$ext_tab}_{$included_column}";

                $join_layer['joins'] .= " JOIN $ext_tab ON {$this->table}.{$int_col} = {$ext_tab}.{$ext_col}";
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

        foreach ($filters as $filterKey => $filterValue) {
            if (is_array($filterValue)) {
                if (isset($filterValue['relatedTable'], $filterValue['values'], $filterValue['wantedColumn'])) {
                    // Adjusted to pass current filter info
                    $result = $this->executeRelatedTableFilterAndGetIds($filterKey, $filterValue);
                    $sql_filters .= ' AND ' . $result;
                } else {
                    // Assuming range conditions are structured as arrays
                    $result = $this->handleRangeCondition($filterKey, $filterValue);
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
