<?php

require_once "$path/controllers/base_controller.php";

class BaseController
{
    protected $central_controller;
    protected $model;
    protected $restricted_columns = [];

    public function __construct($central_controller)
    {
        $this->central_controller = $central_controller;
    }

    // ZI

    protected function restrictAccess($included_columns = [])
    {
        if (!is_array($included_columns) || count($included_columns) === 0) {
            $included_columns = $this->model->columns;
        }

        // $diff = array_diff($included_columns, $this->restricted_columns);
        // echo '<br>' . print_r($this->model->columns);
        // echo '<br>' . print_r($included_columns);
        // echo '<br>' . print_r($diff);

        return array_diff($included_columns, $this->restricted_columns);

        // return array_diff($this->model->columns, $this->restricted_columns);
        return $included_columns;
        // return array_filter($included_columns, function ($key) {
        //     return !in_array($key, $this->restricted_columns);
        // }, ARRAY_FILTER_USE_KEY);
    }

    // ACCESS

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


    protected function getGameTagsController()
    {
        return $this->central_controller->gamestags_contoller;
    }

    // GOOGLE

    protected function getGoogleClientManager()
    {
        return $this->central_controller->client_manager;
    }

    protected function getDriveController()
    {
        return $this->getGoogleClientManager()->drive_controller;
    }

    // CRUDS

    public function getAllMatching($filters = [], $sorting = [], $included_columns = [])
    {
        $included_columns = $this->restrictAccess($included_columns);

        return $this->model->getAllMatching($filters, $sorting, $included_columns);
    }

    public function getOne($column, $value, $included_columns = [])
    {
        $included_columns = $this->restrictAccess($included_columns);

        return $this->model->getOne($column, $value, $included_columns);
    }


    public function getAll($column = null, $value = null, $included_columns = [], $sorting = [])
    {
        $included_columns = $this->restrictAccess($included_columns);

        return $this->model->getAll($column, $value, $included_columns, $sorting);
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        return $this->model->update($id, $data);
    }

    public function delete($id)
    {
        return $this->model->delete($id);
    }

    // REBECCA

    public function createResponse($isSuccess, $message)
    {
        $response = [
            'isSuccessful' => (bool) $isSuccess,
            'message' => $message,
        ];

        return json_encode($response);
    }
}
