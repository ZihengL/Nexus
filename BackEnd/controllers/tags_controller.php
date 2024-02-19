<?php

require_once "$path/controllers/base_controller.php";
require_once "$path/models/game_model.php";

class TagsController extends BaseController
{

    // protected $model;
    protected $id = "id";
    protected $name = "name";


    public function __construct($central_controller, $pdo)
    {
        $this->model = new TagsModel($pdo);
        parent::__construct($central_controller);
    }

    // GETTERS

    public function getById($id)
    {
        return $this->model->getOne($this->id, $id);
    }

    public function getByName($name)
    {
        return $this->model->getByName($this->name, $name);
    }

    // Other CRUDs 
}
