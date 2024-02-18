<?php
require_once "$path/models/game_model.php";

class GamesController
{
    protected $model;
    protected $id = "id";
    protected $developerID = "developerID";
    protected $stripeID = "stripeID";
    protected $title = "title";
    protected $files = "files";
    protected $description = "description";
    protected $name = "name";
    protected $rating = "ratingAverage";
    protected $tags = "tags";
    protected $images = "images";
    protected $devNames = "devNames";
    protected $releaseDate = "releaseDate";
    protected $description = "description";
    protected $stripeID = "stripeID";

    public function __construct($pdo)
    {
        $this->model = new GameModel($pdo);
    }

    // GETTERS

    public function getAllMatching($filters = [], $sorting = [], $included_columns = [])
    {
        return $this->model->getAllMatching($filters, $sorting, $included_columns);
    }

    public function getAllGames($included_columns = [])
    {
        return $this->model->getAll();
    }

    public function getAllMatching($filters = [], $sorting = [], $included_columns = [])
    {
        return $this->model->getAllMatching($filters, $sorting, $included_columns);
    }

    public function getAllGames($included_columns = [])
    {
        return $this->model->getAll();
    }

    public function getById($id) {
        return $this->model->getById($id);
    }

    public function getByDeveloperID($developerID)
    {
        return $this->model->getAll($this->developerID, $developerID);
    }

    public function getByStripeID($stripeID)
    {
        return $this->model->getAll($this->stripeID, $stripeID);
    }

    public function getByTitle($title)
    {
        return $this->model->getAll($this->title, $title);
    }

    public function getByDescription($description)
    {
        return $this->model->getAll($this->description, $description);
    }

    public function getByTags($tags)
    {
        return $this->model->getAll($this->tags, $tags);
    }

    public function getByMedia($media)
    {
        return $this->model->getAll($this->media, $media);
    }

    public function getAllGames()
    {
        return $this->model->getAllGames();
    }

    // Other CRUDs 

    public function addGame($data)
    {
        return $this->model->addGame($data);
    }

    public function updateGame($id, $data)
    {
        return $this->model->updateGame($id, $data);
    }

    public function deleteGame($id)
    {
        return $this->model->deleteGame($id);
    }

    public function applyFiltersAndSorting($filters, $sorting, $includedColumns = null){

        if (empty($sorting)) {
            $sorting = ['ratingAverage' => true];
        }
        return $this->model->applyFiltersAndSorting($filters , $sorting, $includedColumns);
    }

}

