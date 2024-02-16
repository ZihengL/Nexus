<?php
require_once "$path/models/base_model.php";

class GameModel extends BaseModel
{
    protected $tableName = "games";


    public function __construct($pdo)
    {
        parent::__construct($pdo, $this->tableName);
    }

    // public function getByReleaseDate($column, $date)
    // {
    //     return parent::getAll($column, $date);
    // }

    // public function getByTags($column, $tags)
    // {
    //     return parent::getAll($column, $tags);
    // }

    // public function getByDescription($column, $description)
    // {
    //     return parent::getAll($column, $description);
    // }

    // public function getByMedia($column, $media)
    // {
    //     return parent::getAll($column, $media);
    // }

    // public function getByDevs($column, $devName)
    // {
    //     return parent::getAll($column, $devName);
    // }

    public function getAllGames()
    {
        return parent::getAll();
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
    }
}
