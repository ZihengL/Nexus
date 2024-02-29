<?php

require_once "$path/controllers/base_controller.php";
require_once "$path/models/game_model.php";

class GamesController extends BaseController
{
    protected $id = "id";
    protected $developerID = "developerID";
    protected $stripeID = "stripeID";
    protected $title = "title";
    protected $files = "files";
    protected $description = "description";
    protected $ratingAverage = "ratingAverage";
    protected $media = "media";
    protected $releaseDate = "releaseDate";

    public function __construct($central_controller, $pdo)
    {
        $this->model = new GameModel($pdo);
        parent::__construct($central_controller);
    }

    // GETTERS

    // public function getAll($included_columns = [], $sorting = [])
    // {
    //     if (empty($sorting)) {
    //         $sorting = [$this->ratingAverage => true];
    //     }

    //     return $this->model->getAll_games($sorting);
    // }

    public function getAll($column = null, $value = null, $included_columns = [], $sorting = [], $joined_tables = [])
    {
        // $filters = ['tagId' => ['relatedTable' => 'gamesTags', 'values' => ['1', '3'], 'wantedColumn' => 'gameId']];
        // $results_1 = $this->centralController->games_controller->getAllMatching($filters, null, null);
        if (empty($included_columns)) {
            $included_columns = [];
        }

        if (empty($sorting)) {
            $sorting = [$this->ratingAverage => true];
        }

        return $this->model->getAll($column, $value, $included_columns, $sorting, $joined_tables = []);
    }

    public function getAllMatching($filters = [], $sorting = [], $included_columns = [], $joined_tables = [])
    {
        if (empty($sorting)) {
            $sorting = [$this->ratingAverage => true];
        }

        return parent::getAllMatching($filters, $sorting, $included_columns, $joined_tables);
    }

    public function getById($id)
    {
        return $this->model->getOne($this->id, $id);
    }

    public function getByDeveloperID($developerID)
    {
        return $this->model->getOne($this->developerID, $developerID);
    }

    public function getByStripeID($stripeID)
    {
        return $this->model->getOne($this->stripeID, $stripeID);
    }

    public function getByTitle($title)
    {
        return $this->model->getOne($this->title, $title);
    }

    public function getByDescription($description)
    {
        return $this->model->getOne($this->description, $description);
    }

    public function getByMedia($media)
    {
        return $this->model->getOne($this->media, $media);
    }

    // Other CRUDs 

    // TODO: LINK BACK TO USER AUTH
    public function create($data, $jwts = null)
    {
        return $this->model->create($data);
    }

    public function update($id, $data, $jwts = null)
    {
        return $this->model->update($id, $data);
    }

    public function delete($id, $jwts = null)
    {
        return $this->model->delete($id);
    }
}
