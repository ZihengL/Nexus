<?php

class BaseController
{
    protected $central_controller;
    protected $model;

    public function __construct($central_controller, $model)
    {
        $this->central_controller = $central_controller;
        $this->$model = $model;
    }

    // ZI

    public function getAllMatching($sorting = [])
    {
    }

    // REBECCA
}
