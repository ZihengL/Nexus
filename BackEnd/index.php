<?php

// GLOBALS
global $path;
$path = $_SERVER['DOCUMENT_ROOT'] . '/Nexus/BackEnd/';


// IMPORTS
require_once $path . 'controllers/central_controller.php';
$central_controller = CentralController::getInstance();
