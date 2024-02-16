<?php

// GLOBALS
global $path;
$path = $_SERVER['DOCUMENT_ROOT'] . '/Nexus/BackEnd/';


// IMPORTS
require_once $path . 'controllers/central_controller.php';
$central_controller = CentralController::getInstance();

//
$token_manager = $central_controller->token_manager;

$token = $token_manager->generateRefreshToken(1);
echo $token;
$token_manager->revokeToken($token);

$revoked = $token_manager->getAll();

foreach ($revoked as $token) {
    $exp = $token['exp'];
    echo $token['exp'] . '<br>';
}
