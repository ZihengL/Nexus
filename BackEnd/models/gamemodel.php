<?php

require_once "$path/models/basemodel.php";
// require_once "$path/controllers/gamesco.php";

class GameModel extends BaseModel {
    public function __construct($pdo) {
        $table = "games";
        // $fields = ["name", "type", "color", "size", "price", "images", "description"];

        parent::__construct($pdo, $table);
    }

    public function getById($id, $columns = []) {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch();
    }
    
    public function getByReleaseDate($date){
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE date = ?");
        $stmt->execute([$date]);

        return $stmt->fetch();
    }

    public function getByTags($tags) {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE tags = ?");
        $stmt->execute([$tags]);

        return $stmt->fetch();
    }
    
    public function getByDescription($description){
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE description = ?");
        $stmt->execute([$description]);

        return $stmt->fetch();
    }

    
    public function getByImages($img) {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE imgs = ?");
        $stmt->execute([$img]);

        return $stmt->fetch();
    }
    
    public function getByDevs($devName){
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE devName = ?");
        $stmt->execute([$devName]);

        return $stmt->fetch();
    }

    
    public function getPriceRange($filters) {
        $minPrice = intval($filters['minprice']);
        $maxPrice = intval($filters['maxprice']) === 0 ? 999999 : $filters['maxprice'];
    
        return "$minPrice, $maxPrice";
    }

    // public function getAll() {
    //     $stmt = $this->pdo->prepare("SELECT * FROM $this->table");
    //     $stmt->execute();

    //     return $stmt->fetch();
    // }


    //Other Cruds

    public function addGame($game) {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE game = ?");
        $stmt->execute([$game]);

        return $stmt->fetch();
    }
    
    public function updateGame($id,$Game){
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

    public function deleteGame($id){
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch();
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