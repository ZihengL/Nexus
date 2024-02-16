<?php
require_once "$path/models/game_model.php";

class GamesController
{
    protected $model;
    protected $id = "id";
    protected $title = "title";
    protected $rating = "rating";
    protected $tags = "tags";
    protected $media = "media";
    protected $developerID = "developerID";
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

    public function getById($id)
    {
        return $this->model->getById($this->id);
    }

    public function getByDeveloperID($developerID)
    {
        return $this->model->getAll($this->developerID, $developerID);
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
    public function getByReleaseDate($releaseDate)
    {
        return $this->model->getAll($this->releaseDate, $releaseDate);
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

    public function applyFiltersAndSorting($filters, $sorting)
    {
        return $this->model->applyFiltersAndSorting($filters, $sorting);
    }
}
