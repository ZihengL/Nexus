<?php

class BaseModel
{
    public static $print_errors = false;

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
        // $sql .= $filtering_sql . (!empty($joined_tables) ? " GROUP BY {$this->table}.id" : '');

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

        $sql = "SELECT 
                    $int_col, $ext_col, $ext_tab 
                FROM 
                    INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE 
                    TABLE_SCHEMA = '{$_ENV['DB_NAME']}' 
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


    // TODO: IF MULTIPLICITY CAN BE COMPUTED, CHANGE THE JOIN TYPE HERE
    //https://www.w3schools.com/sql/sql_join.asp#:~:text=Different%20Types%20of%20SQL%20JOINs,records%20from%20the%20right%20table
    public function parseJoinedTables($joined_tables = [])
    {
        $selects = '';
        $joins = '';

        foreach ($joined_tables as $ref_tab => $included_columns)
            if (isset($this->keys[$ref_tab])) {
                ['INT_COL' => $int_col, 'EXT_COL' => $ext_col] = $this->keys[$ref_tab];

                foreach ($included_columns as $join_column)
                    $selects .= ", GROUP_CONCAT({$ref_tab}.{$join_column} SEPARATOR ', ') AS {$ref_tab}_{$join_column}";

                $joins .= " INNER JOIN $ref_tab ON {$this->table}.{$int_col} = {$ref_tab}.{$ext_col}";
            }

        // $join_layer['selects'] .= !empty($join_layer['selects']) ? " GROUP BY {$this->table}.id" : '';
        $group = empty($selects) ? " GROUP BY {$this->table}.id" : '';
        return ['selects' => $selects, 'joins' => $joins, 'group' => $group];
    }


    /*******************************************************************/
    /************************** SIEVING LAYER **************************/
    /*******************************************************************/

    public function getPaging($limit = -1, $offset = 0)
    {
        if ($limit !== -1)
            return " LIMIT $limit OFFSET $offset";

        return '';
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
        // $relatedTable = $filterValue['relatedTable'];
        // $filter_values = $filterValue['values'];
        // $included_columns = $filterValue['wantedColumn'];

        // Your existing SQL construction
        $placeholders = implode(', ', array_fill(0, count($filter_values), '?'));
        $sql = "SELECT {$included_columns} 
                FROM {$related_table}  
                WHERE {$filterKey} 
                IN ($placeholders)";

        // Prepare and execute the query
        // $stmt = $this->pdo->prepare($sql);
        // $stmt->execute($filter_values);
        // Was executing it outside of the query with catch
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
