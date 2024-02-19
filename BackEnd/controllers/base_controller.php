<?php

require_once "$path/controllers/base_controller.php";

class BaseController
{
    protected $central_controller;
    protected $model;

    public function __construct($central_controller)
    {
        $this->central_controller = $central_controller;
    }

    // ZI

    protected function create($data)
    {
        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        return $this->model->update($id, $data);
    }

    protected function delete($id)
    {
        return $this->model->delete($id);
    }

    protected function getDatabaseManager()
    {
        return $this->central_controller->DatabaseManager;
    }

    protected function getUsersController()
    {
        return $this->central_controller->users_controller;
    }

    protected function getGamesController()
    {
        return $this->central_controller->games_controller;
    }

    protected function getDriveController()
    {
        // return $this->central_controller->games_controller;
    }

    protected function getTokensController()
    {
        return $this->central_controller->tokens_controller;
    }

    protected function getTagsController()
    {
        return $this->central_controller->tags_controller;
    }

    protected function getReviewsController()
    {
        return $this->central_controller->reviews_controller;
    }

    public function getOne($column, $value, $included_columns = [])
    {
        return $this->model->getOne($column, $value, $included_columns);
    }

    public function getAllMatching($sorting = [])
    {
    }

    // REBECCA
}
