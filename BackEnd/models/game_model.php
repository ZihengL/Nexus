<?php

require_once "$path/models/base_model.php";
// require_once "$path/controllers/games.php";

class GameModel extends BaseModel
{

    protected $tableName = "games";


    public function __construct($pdo)
    {
        parent::__construct($pdo, $this->tableName);
    }

    public function getByReleaseDate($columnName, $date)
    {
        return parent::getAll($columnName, $date);
    }

    public function getByTags($columnName, $tags)
    {
        return parent::getAll($columnName, $tags);
    }

    public function getByDescription($columnName, $description)
    {
        return parent::getAll($columnName, $description);
    }

    public function getByImages($columnName, $img)
    {
        return parent::getAll($columnName, $img);
    }

    public function getByDevs($columnName, $devName)
    {
        return parent::getAll($columnName, $devName);
    }

    public function getAllGames()
    {
        return parent::getAll($column = null, $value = null, $columns = []);
    }

    public function getMinMaxPrice()
    {
        $sql = "SELECT MIN(price) AS minPrice, MAX(price) AS maxPrice FROM $this->tableName";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return [
                'minPrice' => $result['minPrice'],
                'maxPrice' => $result['maxPrice']
            ];
        } else {
            return [
                'minPrice' => null,
                'maxPrice' => null
            ];
        }
    }


    // public function getPriceRange($filters) {
    //     $minPrice = intval($filters['minprice']);
    //     $maxPrice = intval($filters['maxprice']) === 0 ? 999999 : $filters['maxprice'];

    //     return "$minPrice, $maxPrice";
    // }


    //Other Cruds

    public function applyFiltersAndSorting($filters, $sorting)
    {
        return parent::applyFiltersAndSorting($filters, $sorting);
    }

    public function addGame($game)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE game = ?");
        $stmt->execute([$game]);

        return $stmt->fetch();
    }

    public function updateGame($id, $Game)
    {
        $formattedData = $this->formatData($Game);
        $pairs = implode(' = ?, ', array_keys($formattedData)) . ' = ?';
        $formattedData['id'] = $id;

        $sql = "UPDATE $this->table SET $pairs WHERE id = ?";
        // print_r($sql);
        if ($this->query($sql, $formattedData)) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteGame($id)
    {
        return parent::delete($id);
        // $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE id = ?");
        // $stmt->execute([$id]);

        // return $stmt->fetch();
    }

    // public function filter($filters = [], $columns = []) {
    //     $priceRange = $this->getPriceRange($filters);
    //     unset($filters['minprice'], $filters['maxprice']);

    //     $filters = array_filter($filters, fn($filter) => !empty($filter));
    //     $mappedKeys = $this->implodeFiltersMap($filters);

    //     $sql =  "SELECT " . parseColumns($columns) . " FROM $this->table" .
    //             (!empty($mappedKeys) || !empty($priceRange) ? " WHERE " : "");

    //     if (!empty($mappedKeys)) {
    //         $sql .= $mappedKeys . (!empty($priceRange) ? " AND " : "");
    //     }
    //     $sql .= $priceRange;

    //     // TO DELETE
    //     // print($sql . "<br>");

    //     return $this->bindingQuery($sql, $filters);
    // }

    // public function implodeFiltersMap($filters) {
    //     $mappedKeys = array_map(fn($filter) => $filter . " = :$filter", array_keys($filters));

    //     return implode(' AND ', $mappedKeys);
    // }



}
