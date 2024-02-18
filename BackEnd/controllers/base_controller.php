<?php

// require_once "$path/controllers/central_controller.php";

class BaseController
{
    // Alternate solutions to giving controllers universal access to central_controller?
    // protected static $central_controller;
    protected static $pdo;

    // Another solution
    protected $central_controller;
    protected $table;
    protected $model;

    public function __construct($central_controller, $table, $model)
    {
        $this->central_controller = $central_controller;
        $this->table = $table;
        $this->$model = $model;
    }

    // public static function setupAccess($central_controller, $pdo)
    // {
    //     self::$central_controller = $central_controller;
    //     self::$pdo = $pdo;
    // }

    // One other alternate solution
    // protected static function getControllersAccess()
    // {
    //     return CentralController::getInstance();
    // }

    // protected function getControllersAccess2()
    // {
    //     return CentralController::getInstance();
    // }

    public function getAllMatching()
    {
    }
}
