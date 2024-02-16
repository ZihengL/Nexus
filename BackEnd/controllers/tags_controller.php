<?php
require_once "$path/models/game_model.php";

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

    public function getAllTags() {
        return $this->model->getAll_tags();
    }

    // Other CRUDs 

    public function applyFiltersAndSorting($filters, $sorting = null){
        return $this->model->applyFiltersAndSorting($filters , $sorting );
    }

    public function delete($id) {
        return $this->model->delete($id);
    }

}

