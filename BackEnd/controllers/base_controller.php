<?php

// require_once "$path/controllers/central_controller.php";

class BaseController
{
    protected $central_controller;
    protected $table;
    protected $model;

    public function __construct($central_controller, $model)
    {
        $this->central_controller = $central_controller;
        $this->$model = $model;
    }

    public function getAllMatching($sorting = [])
    {
    }
}
