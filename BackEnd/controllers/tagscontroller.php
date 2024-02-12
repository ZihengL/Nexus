<?php
require_once "$path/models/gamemodel.php";

class TagsController {

    protected $model;
    protected $id = "id";
    protected $name = "name";
    

    public function __construct($pdo) {
        $this->model = new TagsModel($pdo);
    }

    // GETTERS

    public function getById($id) {
        return $this->model->getById($this->id);
    }

    public function getByName($name) {
        return $this->model->getByName($this->name, $name);
    }

    public function getAll() {
        return $this->model->getAll();
    }

    // Other CRUDs 

    public function applyFiltersAndSorting($filters, $sorting){
        return $this->model->applyFiltersAndSorting($filters , $sorting );
    }

}

