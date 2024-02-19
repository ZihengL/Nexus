<?php


global $path;
$path = $_SERVER['DOCUMENT_ROOT'] . '/Nexus/BackEnd';

require_once $path . '/controllers/central_Controller.php';

$centralController = CentralController::getInstance();
$databaseManager = $centralController->database_manager;












