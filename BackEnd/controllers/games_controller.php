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
    protected $rating = "ratingAverage";
    protected $media = "media";
    protected $images = "images";
    protected $releaseDate = "releaseDate";

    public function __construct($pdo)
    {
        $this->model = new GameModel($pdo);
    }

    // GETTERS

    public function getAllMatching($filters = [], $sorting = [], $included_columns = [])
    {
        return $this->model->getAllMatching($filters, $sorting, $included_columns);
    }

    public function getAll_games($included_columns = [], $sorting = ['ratingAverage' => true])
    {
        return $this->model->getAll_games($sorting);
    }


    public function getById($id) {
        return $this->model->getById($id);
    }

    public function getByDeveloperID($developerID)
    {
        return $this->model->getByColumn($this->developerID, $developerID);
    }

    public function getByStripeID($stripeID)
    {
        return $this->model->getByColumn($this->stripeID, $stripeID);
    }

    public function getByTitle($title)
    {
        return $this->model->getByColumn($this->title, $title);
    }

    public function getByDescription($description)
    {
        return $this->model->getByColumn($this->description, $description);
    }

    public function getByMedia($media)
    {
        return $this->model->getByColumn($this->media, $media);
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

