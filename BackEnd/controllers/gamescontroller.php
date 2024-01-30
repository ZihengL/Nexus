<?php
require_once "$path/models/gamemodel.php";

class GamesController {
    private $model;
    private $name = "name";
    private $tags = "tags";
    private $price = "price";
    private $images = "images";
    private $releaseDate = "releaseDate"; 
    private $description = "description";
    private $stripeID = "stripeID";
  


    public function __construct($pdo) {
        $this->model = new GameModel($pdo);
    }

    // GETTERS

    public function getAllProducts() {
        return $this->model->getAll();
    }

    public function getProductById($id) {
        return $this->model->getById($id);
    }


    public function getProductByName($name) {
        return $this->model->get($this->name, $name);
    }

    public function getByTags($tags) {
        if (is_array($tags)) {
            return $this->model->get($this->tags, $tags);
        }

        return false;
    }

    public function getProductByPrice($price) {
        return $this->model->get($this->price, $price);
    }

    public function getByImages($images) {
        return $this->model->get($this->images, $images);
    }

    public function getByDescription($description) {
        return $this->model->get($this->description, $description);
    }

    public function getByReleaseDate($releaseDate) {
        return $this->model->get($this->releaseDate, $releaseDate);
    }

    public function getProductByStripeID($stripeID) {
        return $this->model->get($this->stripeID, $stripeID);
    }

    // OTHER CRUDS

    public function addGame($data) {
        return $this->model->create($data);
    }

    public function updateGame($id, $data) {
        return $this->model->update($id, $data);
    }

    public function deleteGame($id) {
        return $this->model->delete($id);
    }

    public function filterGames($filters, $columns = []) {
        return $this->model->filterGames($filters, $columns);
    }


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