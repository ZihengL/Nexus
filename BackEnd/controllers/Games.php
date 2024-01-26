<?php

require_once "$path/models/product.php";

class ProductsController {
    private $model;

    public function __construct($pdo) {
        $this->model = new ProductModel($pdo);
    }

    // GETTERS

    public function getAllFields() {
        return $this->model->fields;
    }

    public function getField($fieldIndex) {
        if ($fieldIndex < count($this->model->fields)) {
            return $this->model->fields[$fieldIndex];
        }

        return null;
    }

    public function getAllProducts() {
        return $this->model->getAll();
    }

    public function getProductById($id) {
        return $this->model->getById($id);
    }

    public function getProductsByGender($gender) {
        return $this->model->get('gender', $gender);
    }

    public function getProductByName($name) {
        return $this->model->get('name', $name);
    }

    public function getProductByType($type) {
        return $this->model->get('type', $type);
    }

    public function getProductByColor($color) {
        return $this->model->get('color', $color);
    }

    public function getProductBySize($size) {
        return $this->model->get('size', $size);
    }

    public function getProductByPrice($price) {
        return $this->model->get('price', $price);
    }

    public function getProductByImages($images) {
        return $this->model->get('images', $images);
    }

    public function getProductByDescription($description) {
        return $this->model->get('description', $description);
    }

    public function getProductByStripeID($stripeID) {
        return $this->model->get('stripeID', $stripeID);
    }

    // OTHER CRUDS

    public function createProduct($data) {
        return $this->model->create($data);
    }

    public function updateProduct($id, $data) {
        return $this->model->update($id, $data);
    }

    public function deleteProduct($id) {
        return $this->model->delete($id);
    }

    public function filter($filters, $columns = []) {
        return $this->model->filter($filters, $columns);
    }

    // public function filterProducts($type, $color, $size, $minprice, $maxprice) {
    //     return $this->model->filterProduct($type, $color, $size, $minprice, $maxprice);
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