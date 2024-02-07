<?php
require_once "$path/models/game.php";

class GamesController {
    protected $model;

    public function __construct($pdo) {
        $this->model = new GameModel($pdo);
    }

    // GETTERS

    public function getById($id) {
        return $this->model->getById($id);
    }

    public function getByReleaseDate($releaseDate) {
        return $this->model->getByReleaseDate($releaseDate);
    }

    public function getByTags($tags) {
        return $this->model->getByTags($tags);
    }

    public function getByDescription($description) {
        return $this->model->getByDescription($description);
    }

    public function getByImages($images) {
        return $this->model->getByImages($images);
    }

    public function getByDevs($devName) {
        return $this->model->getByDevs($devName);
    }

    public function getMinMaxPrice() {
        return $this->model->getMinMaxPrice();
    }
    
    public function getAllGames() {
        return $this->model->getAllGames();
    }


    // Other CRUDs 
    public function addGame($data) {
        return $this->model->addGame($data);
    }

    public function updateGame($id, $data) {
        return $this->model->updateGame($id, $data);
    }

    public function deleteGame($id) {
        return $this->model->deleteGame($id);
    }

    // public function filterGames($filters, $columns = []) {
    //     return $this->model->filterGames($filters, $columns);
    // }


    // public function getAllFields() {
    //     return $this->model->fields;
    // }

    // public function getField($fieldIndex) {
    //     if ($fieldIndex < count($this->model->fields)) {
    //         return $this->model->fields[$fieldIndex];
    //     }

    //     return null;
    // }

    // public function filterProducts($tags, $color, $size, $minprice, $maxprice) {
    //     return $this->model->filterProduct($tags, $color, $size, $minprice, $maxprice);
    // }

    // public function getFilterMap() {
    //     return $this->model->getMappedSets();
    // }

    // public function filterProductByField($products, $field, $value) {
    //     $filtered = [];

    //     foreach ($products as $product) {
    //         $fieldValue = $product[$field];

    //         if ($product[$field] == $value || (is_array($product[$field]) && in_array($product[$field], $value))) {
    //             $filtered[] = $product;
    //         }
    //     }

    //     return $filtered;
    // }

    // public function filterProductByPriceRange($products, $lowest, $highest) {
    //     $filtered = [];

    //     foreach ($products as $product) {
    //         if ($product['price'] >= $lowest) {
    //             if ($product['price'] <= $highest || $highest == -1) {
    //                 $filtered[] = $product;
    //             }
    //         }
    //     }

    //     return $filtered;
    // }

    // public function setSessionProduct($id) {
    //     if (session_status() !== PHP_SESSION_ACTIVE) {
    //         session_start();
    //     }

    //     $_SESSION['productId'] = $id;
    // }

    // public function getMappedValues() {
    //     return $this->model->getMappedValues();
    // }
}