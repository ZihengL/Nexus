<?php

class BaseModel
{
    public static $database;
    public static $print_errors = false;

    protected static $models = [];

    protected $pdo;
    public $table;
    public $columns = [];
    protected $keys = [];

    protected function __construct($pdo, $table, $require_id = false)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->columns = $this->getColumns($require_id);

        foreach ([...$this->getKeysDetails(false), ...$this->getKeysDetails(true)] as $key)
            $this->keys[array_shift($key)] = $key;

        self::$models[$this->table] = $this;
    }

    private function addDeconstructedKeys($keys)
    {
        if (!empty($keys) && isset($keys['table']))
            $this->keys[array_shift($keys)] = $keys;
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

            return $stmt;
        } catch (PDOException $e) {
            if (self::$print_errors) {
                echo "<h5>{$this->table}</h5><br><pre>";
                var_dump($stmt);
                var_dump($params);
                echo '</pre><hr>';
            }

            throw new Exception("Database query error: " . $e->getMessage());
        }
    }

    protected function bindingQuery($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);

            foreach ($params as $column => $value) {
                $stmt->bindValue($column, $value);
            }
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            if (self::$print_errors) {
                echo "<h5>{$this->table}</h5><br><pre>";
                var_dump($stmt);
                var_dump($params);
                echo '</pre><hr>';
            }

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
    /****************************** CRUDS ******************************/
    /*******************************************************************/

    public function create($data)
    {
        $data = $this->formatData($data);
        $columns = implode(', ', array_keys($data));
        $placeholders = substr(str_repeat(", ?", count($data)), 1);

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";

        if ($this->query($sql, $data)) {
            return true;
        } else {

            return false;
        }
    }

    public function update($id, $data)
    {
        $formatted_data = $this->formatData($data);

        if (!empty($formatted_data)) {
            $pairs = implode(' = ?, ', array_keys($formatted_data)) . ' = ?';
            $formatted_data['id'] = $id;

            $sql = "UPDATE {$this->table} SET $pairs WHERE id = ?";

            return $this->query($sql, $formatted_data)->fetch();
        }

        throw new Exception("Update operation failed on {$this->table} for id '$id' with datas: " . unwrap($data));
    }

    public function delete($id)
    {
        return $this->query("DELETE FROM {$this->table} WHERE id = $id")->rowCount();
    }

    public function formatData($data)
    {
        $formatted = [];

        foreach ($this->getColumns(false) as $column) {
            if (in_array($column, array_keys($data)))
                $formatted[$column] = $data[$column];
        }
        return $formatted;
    }


    /*******************************************************************/
    /***************************** GETTERS *****************************/
    /*******************************************************************/

    public function getOne($column, $value, $included_columns = [], $joined_tables = [])
    {
        ['selections' => $selections, 'group' => $group] = $this->buildSelectionLayer($included_columns, $joined_tables);

        // $sql = "$selections WHERE {$this->table}.$column = ?" . (count($joined_tables) > 0 ? " GROUP BY {$this->table}.id" : '');
        $sql = "$selections WHERE {$this->table}.$column = ? $group";
        // $result = $this->query($sql, [$value]);

        return $this->query($sql, [$value])->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll($column = null, $value = null, $included_columns = [], $sorting = [], $joined_tables = [], $paging = [])
    {
        ['selections' => $selections, 'group' => $group] = $this->buildSelectionLayer($included_columns, $joined_tables);

        $sql = $selections;
        $params = [];
        if ($column && $value) {
            $sql .= " WHERE {$this->table}.$column = ?";
            $params = [$value];
        }

        $sort_layer = $this->applySorting($sorting);
        // $sql .= !empty($joined_tables) ? " GROUP BY {$this->table}.id" : '';
        $sql .= $group . (!empty($sort_layer) ? " ORDER BY $sort_layer" : '') . $this->getPaging(...$paging);
        // $sql .= $this->getPaging($paging['limit'] ?? -1, $paging['offset'] ?? 0);   // TESTING PAGING

        return $this->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllMatching($filters = [], $sorting = [], $included_columns = [], $joined_tables = [], $paging = [])
    {
        ['selections' => $selections, 'group' => $group] = $this->buildSelectionLayer($included_columns, $joined_tables);
        ['sql' => $filtering_sql, 'params' => $params] = $this->applyFilters($filters);

        $sql = "$selections WHERE 1 = 1 $filtering_sql $group";

        if ($sorting_layer = $this->applySorting($sorting))
            $sql .= " ORDER BY $sorting_layer";

        $sql .= $this->getPaging(...$paging);

        return $this->bindingQuery($sql, $params);
    }


    /*******************************************************************/
    /************************* SELECTION LAYER *************************/
    /*******************************************************************/

    public function getColumns($include_id = false)
    {
        $result = $this->query("DESCRIBE {$this->table}")->fetchAll(PDO::FETCH_COLUMN);

        if (!$include_id)
            array_shift($result);

        return $result;
    }


    // if ($is_internal_keys) {
    //     $sql =  "SELECT 
    //                 kcu.TABLE_NAME AS 'table',
    //                 kcu.COLUMN_NAME AS 'external', 
    //                 kcu.REFERENCED_COLUMN_NAME AS 'internal', 
    //                 (SELECT COUNT(*)
    //                     FROM 
    //                         INFORMATION_SCHEMA.KEY_COLUMN_USAGE
    //                     WHERE 
    //                         CONSTRAINT_NAME = kcu.CONSTRAINT_NAME 
    //                         AND TABLE_SCHEMA = '$database'
    //                         AND TABLE_NAME = kcu.TABLE_NAME) AS 'composition'
    //             FROM 
    //                 INFORMATION_SCHEMA.KEY_COLUMN_USAGE AS kcu
    //             WHERE 
    //                 kcu.TABLE_SCHEMA = '$database' 
    //                 AND kcu.REFERENCED_TABLE_NAME = '{$this->table}'";
    // } else {
    //     $sql = "SELECT 
    //                 kcu.REFERENCED_TABLE_NAME AS 'table',
    //                 kcu.REFERENCED_COLUMN_NAME AS 'external', 
    //                 kcu.COLUMN_NAME AS 'internal', 
    //                 (SELECT COUNT(*)
    //                     FROM 
    //                         INFORMATION_SCHEMA.KEY_COLUMN_USAGE AS inner_kcu
    //                     JOIN 
    //                         INFORMATION_SCHEMA.TABLE_CONSTRAINTS AS tc 
    //                         ON inner_kcu.CONSTRAINT_NAME = tc.CONSTRAINT_NAME
    //                         AND inner_kcu.TABLE_SCHEMA = tc.TABLE_SCHEMA 
    //                         AND inner_kcu.TABLE_NAME = tc.TABLE_NAME
    //                     WHERE 
    //                         tc.TABLE_SCHEMA = '$database' 
    //                         AND tc.TABLE_NAME = '{$this->table}' 
    //                         AND tc.CONSTRAINT_TYPE = 'PRIMARY KEY') AS 'composition'
    //             FROM 
    //                 INFORMATION_SCHEMA.KEY_COLUMN_USAGE AS kcu
    //             WHERE 
    //                 kcu.TABLE_SCHEMA = '$database' 
    //                 AND kcu.TABLE_NAME = '{$this->table}' 
    //                 AND kcu.REFERENCED_TABLE_NAME IS NOT NULL";
    // }

    public function getKeysDetails($is_internal_keys = true)
    {
        $database = self::$database;

        $table = "TABLE_NAME AS 'table'";
        $external = "COLUMN_NAME AS 'external'";
        $internal = "COLUMN_NAME AS 'internal'";

        $table_condition = "TABLE_NAME = '{$this->table}'";

        if ($is_internal_keys) {
            $table = "REFERENCED_$table";
            $external = "REFERENCED_$external";
            $table_condition = "$table_condition AND REFERENCED_TABLE_NAME IS NOT NULL";
        } else {
            $internal = "REFERENCED_$internal";
            $table_condition = "REFERENCED_$table_condition";
        }

        $sql = "SELECT 
                    $table, $external, $internal
                FROM 
                    INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE 
                    TABLE_SCHEMA = '$database' 
                AND 
                    $table_condition";

        $keys_details = $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        return $keys_details;
    }

    public function buildSelectionLayer($included_columns = [], $join_keys = [])
    {
        $columns = $this->parseColumns($included_columns);
        ['selects' => $selects, 'joins' => $joins, 'group' => $group] = $this->parseJoinedTables($join_keys);

        $selections = "SELECT $columns $selects FROM {$this->table} $joins";
        return ['selections' => $selections, 'group' => $group];
    }

    public function parseColumns($included_columns = [])
    {
        if (empty($included_columns))
            return "{$this->table}.*";

        return "{$this->table}." . implode(", {$this->table}.", $included_columns);
    }

    // COMPOSITE STUFF FOR SELECTS (MANY-TO-MANY)

    public function getCompositeModel($ref_table)
    {
        foreach (self::$models as $model)
            if (
                isset($model->keys[$this->table]) &&
                isset($model->keys[$ref_table])
            )
                return $model;

        return null;
    }

    public function getCompositeSelections($from, $with, $included_columns = [])
    {
        $selection = '';
        foreach ($included_columns as $column)
            $selection .= ", GROUP_CONCAT({$with}.{$column} SEPARATOR ', ') AS {$with}_{$column}";

        ['external' => $from_ext_id, 'internal' => $from_int_id] = $this->keys[$from];
        $join = " JOIN {$this->table} ON {$from}.{$from_ext_id} = {$this->table}.{$from_int_id}";

        ['external' => $with_ext_id, 'internal' => $with_int_id] = $this->keys[$with];
        $join .= " JOIN {$with} ON {$this->table}.{$with_int_id} = {$with}.{$with_ext_id}";

        return [$selection, $join];
    }

    public function parseJoinedTables($joined_tables = [])
    {
        $selects = '';
        $joins = '';

        foreach ($joined_tables as $table => $included_columns) {
            if ($composite_model = $this->getCompositeModel($table)) {
                [$selection, $join] = $composite_model->getCompositeSelections($this->table, $table, $included_columns);

                $selects .= $selection;
                $joins .= $join;
            } else {
                if (isset($this->keys[$table])) {
                    ['internal' => $internal, 'external' => $external] = $this->keys[$table];

                    foreach ($included_columns as $column)
                        $selects .= ", {$table}.{$column} AS {$table}_{$column}";

                    $joins .= " JOIN $table ON {$this->table}.{$internal} = {$table}.{$external}";
                }
            }
        }

        // $join_layer['selects'] .= !empty($join_layer['selects']) ? " GROUP BY {$this->table}.id" : '';
        $group = !empty($selects) ? " GROUP BY {$this->table}.id" : '';
        return ['selects' => $selects, 'joins' => $joins, 'group' => $group];
    }


    /*******************************************************************/
    /************************** SIEVING LAYER **************************/
    /*******************************************************************/

    public function getPaging($limit = -1, $offset = 0)
    {
        return $limit !== -1 ? " LIMIT $limit OFFSET $offset" : '';
    }

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
        ['relatedTable' => $related_table, 'values' => $filter_values, 'wantedColumn' => $included_columns] = $filterValue;

        $placeholders = implode(', ', array_fill(0, count($filter_values), '?'));
        $sql = "SELECT {$included_columns} 
                FROM {$related_table}  
                WHERE {$filterKey} 
                IN ($placeholders)";

        $ids = $this->query($sql, $filter_values)->fetchAll(PDO::FETCH_COLUMN, 0);

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
