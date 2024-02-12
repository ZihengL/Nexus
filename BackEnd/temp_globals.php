<?php

function printer($content, $title = null)
{
    $result = $title ?? "<hr><h5> $title </h5><br>";

    if (is_array($content)) {
        $is_dict = array_keys($content) !== range(0, count($content) - 1);

        foreach ($content as $element => $value) {
            if ($is_dict) {
                $result .= "$element: <b>$value</b>";
            } else {
                $result .= "$element <br>";
            }
        }
    } else {
        $result .= $content;
    }

    return $result . '<br>';
}

global $path;
$path = $_SERVER['DOCUMENT_ROOT'] . '/Nexus/BackEnd/';

require_once $path . 'controllers/central_controller.php';
$central_controller = CentralController::getInstance();

$users_controller = $central_controller->users_controller;
$games_controller = $central_controller->games_controller;

// MANAGERS
$token_manager = $central_controller->token_manager;
$database_manager = $central_controller->database_manager;
