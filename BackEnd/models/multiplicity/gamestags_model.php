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

    // public function getOne($column, $value, $included_columns = [], $joined_tables = [])
    // {
    //     $sql = $this->buildSelectionLayer($included_columns, $joined_tables) . " WHERE $this->table.$column = ?";


    //     return $this->query($sql, [$value])->fetch(PDO::FETCH_ASSOC);
    // }

    // public function getAll($column = null, $value = null, $included_columns = [], $sorting = [], $joined_tables = [])
    // {
    //     $sql = $this->buildSelectionLayer($included_columns, $joined_tables);
    //     $params = [];

    //     if ($column && $value) {
    //         $sql .= " WHERE $this->table.$column = ?";
    //         $params = [$value];
    //     }

    //     $sort_layer = $this->applySorting($sorting);
    //     $sql .= !empty($sort_layer) ? " ORDER BY $sort_layer" : '';

    //     return $this->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    // }

    // public function getAllMatching($filters = [], $sorting = [], $included_columns = [], $joined_tables = [])
    // {
    //     $sql = $this->buildSelectionLayer($included_columns, $joined_tables) . ' WHERE 1 = 1';

    //     $filterResults = $this->applyFilters($filters);
    //     $sortingResults = $this->applySorting($sorting);

    //     $sqlWithFilters = $filterResults['sql'];
    //     $params = $filterResults['params'];

    //     $sqlWithFiltersAndSorting = $sql . $sortingResults ? "$sqlWithFilters ORDER BY $sortingResults" : $sqlWithFilters;

    //     return $this->bindingQuery($sqlWithFiltersAndSorting, $params);
    // }

    public function getGamesWith($users = true, $tags = false, $filters = [], $sorting = [], $paging = [])
    {
        $selections = "";
        $joins = "";

        if ($users) {
            $selections .= "users.id AS userId, users.username AS username, ";
            $joins .= "LEFT JOIN users ON games.developerID = users.id ";
        }

        if ($tags) {
            $selections .= "GROUP_CONCAT(tags.name SEPARATOR ', ') AS tags, ";
            $joins .= "LEFT JOIN tags ON games.developerID = users.id ";
        }

        $sql = "SELECT $selections games.* 
                FROM games 
                $joins 
                LEFT JOIN
                gamestags ON games.id = gamestags.gameId 
                GROUP BY games.id
                LIMIT 3 OFFSET 2";

        return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buildSelectionLayer($included_columns = [], $join_keys = [])
    {
    }
}
