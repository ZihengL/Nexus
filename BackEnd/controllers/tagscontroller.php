<?php
require_once "$path/models/gamemodel.php";

class GamesController {

    protected $model;
    protected $id = "id";
    protected $name = "name";
    

    public function __construct($pdo) {
        $this->model = new GameModel($pdo);
    }

    // GETTERS

    public function getById($id) {
        return $this->model->getById($this->id, $id);
    }

    public function getByName($releaseDate) {
        return $this->model->getByReleaseDate($this->releaseDate, $releaseDate);
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

    public function applyFiltersAndSorting($filters, $sorting){
        return $this->model->applyFiltersAndSorting($filters , $sorting );
    }

}

