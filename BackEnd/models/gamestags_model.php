<?php

require_once "$path/models/base_model.php";

class GamestagsModel extends BaseModel
{
    public function __construct($pdo)
    {
        parent::__construct($pdo, "gamestags");
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
}
