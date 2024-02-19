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

    public function getAllMatching($sorting = [])
    {
    }

    // REBECCA
}
