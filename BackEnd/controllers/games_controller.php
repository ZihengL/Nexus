<?php
require_once "$path/models/game_model.php";

class GamesController {

    protected $model;
    protected $id = "id";
    protected $name = "name";
    protected $rating = "rating";
    protected $tags = "tags";
    protected $price = "price";
    protected $images = "images";
    protected $devNames = "devNames";
    protected $releaseDate = "releaseDate"; 
    protected $description = "description";
    protected $stripeID = "stripeID";

    public function __construct($pdo) {
        $this->model = new GameModel($pdo);
    }

    // GETTERS

    public function getById($id) {
        return $this->model->getById($this->id);
    }

    public function getByReleaseDate($releaseDate) {
        return $this->model->getByReleaseDate($this->releaseDate, $releaseDate);
    }

    public function getByTags($tags) {
        return $this->model->getByTags($this->tags, $tags);
    }

    public function getByDescription($description) {
        return $this->model->getByDescription($this->description, $description);
    }

    public function getByImages($images) {
        return $this->model->getByImages($this->images, $images);
    }

    public function getByDevs($devName) {
        return $this->model->getByDevs($this->devName, $devName);
    }
    
    public function getAllGames() {
        return $this->model->getAllGames();
    }


    // Other CRUDs 

    //Create/Add games
    public function addGame($data) {
        return $this->model->addGame($data);
    }


    //Validate games before adding them in the database



    //update and delete games
    //determine what can be updated or not
    public function updateGame($id, $data) {
        return $this->model->updateGame($id, $data);
    }

    public function deleteGame($id) {
        return $this->model->deleteGame($id);
    }

    public function applyFiltersAndSorting($filters, $sorting){
        return $this->model->applyFiltersAndSorting($filters , $sorting );
    }

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