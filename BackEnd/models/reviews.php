<?php

require_once "$path/models/basemodel.php";
require_once "$path/controllers/products.php";

class ProductModel extends BaseModel {
    public function __construct($pdo) {
        $table = "product";
        $fields = ["name", "type", "color", "size", "price", "images", "description"];

        parent::__construct($pdo, $table, $fields);
    }

    
    
    public function getProductByImages($images) {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE images = ?");
        $stmt->execute([$images]);

        return $stmt->fetch();
    }

    public function filter($filters = [], $columns = []) {
        $priceRange = $this->getPriceRange($filters);
        unset($filters['minprice'], $filters['maxprice']);

        $filters = array_filter($filters, fn($filter) => !empty($filter));
        $mappedKeys = $this->implodeFiltersMap($filters);

        $sql =  "SELECT " . parseColumns($columns) . " FROM $this->table" .
                (!empty($mappedKeys) || !empty($priceRange) ? " WHERE " : "");

        if (!empty($mappedKeys)) {
            $sql .= $mappedKeys . (!empty($priceRange) ? " AND " : "");
        }
        $sql .= $priceRange;

        // TO DELETE
        // print($sql . "<br>");

        return $this->bindingQuery($sql, $filters);
    }

    public function getPriceRange($filters) {
        $minPrice = intval($filters['minprice']);
        $maxPrice = intval($filters['maxprice']) === 0 ? 999999 : $filters['maxprice'];
    
        return "price BETWEEN $minPrice AND $maxPrice";
    }

    public function implodeFiltersMap($filters) {
        $mappedKeys = array_map(fn($filter) => $filter . " = :$filter", array_keys($filters));

        return implode(' AND ', $mappedKeys);
    }


    // GETTERS

    // public function getProductById($id) {
    //     return $this->get("id", $id);
    // }

    // public function getProductByName($name) {
    //     return $this->get($this->fields[0], $name);
    // }

    // public function getProductByType($type) {
    //     return $this->get($this->fields[1], $type);
    // }

    // public function getProductByColor($color) {
    //     return $this->get($this->fields[2], $color);
    // }

    // public function getProductBySize($size) {
    //     return $this->get($this->fields[3], $size);
    // }

    // public function getProductByPrice($price) {
    //     return $this->get($this->fields[4], $price);
    // }

    // public function getProductByDescription($description) {
    //     return $this->get($this->fields[6], $description);
    // }

    // FILTERING

    // public function getProductUnderPrice($price) {
    //     $sql = "SELECT * FROM $this->table WHERE price < ?";

    //     return $this->query($sql, [$price]);
    // }

    // public function getProductOverPrice($price) {
    //     $sql = "SELECT * FROM $this->table WHERE price > ?";

    //     return $this->query($sql, [$price]);
    // }

    // public function getProductInPriceRange($lowest, $highest) {
    //     $sql = "SELECT * FROM $this->table WHERE price > ? AND price < ?";

    //     return $this->query($sql, [$lowest, $highest]);
    // }
}