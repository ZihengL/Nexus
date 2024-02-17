<?php
require_once "$path/models/game_model.php";

class GamesController {

    protected $model;
    protected $id = "id";
    protected $name = "name";
    protected $rating = "ratingAverage";
    protected $tags = "tags";
    protected $images = "images";
    protected $videos = "videos"; //missing
    protected $files = "files"; // missing
    protected $devNames = "developperID";
    protected $releaseDate = "releaseDate"; 
    protected $description = "description";
    protected $stripeID = "stripeID"; // not sure yet

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
        return $this->model->getByDevs($this->devNames, $devName);
    }
    
    public function getAll_games($sorting = ['ratingAverage' => true]) {
        return $this->model->getAll_games($sorting);
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

    public function applyFiltersAndSorting($filters, $sorting = null, $includedColumns = null){
        return $this->model->applyFiltersAndSorting($filters , $sorting, $includedColumns);
    }

}

