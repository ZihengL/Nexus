<?php

function printer($content, $title = '')
{
    if (strlen($title) > 0) {
        echo '<hr><h5>' . $title . '</h5><br>';
    }

    if (is_array($content)) {
        if (array_keys($content) !== range(0, count($content) - 1)) {
            foreach ($content as $element => $value) {
                $subcontent = "$element: <b>$value</b>";
                printer($subcontent);
            }
        } else {
            foreach ($content as $element) {
                printer($element);
            }
        }

        echo '<br>';
    } else {
        echo "$content<br>";
    }
}

global $path;
$path = $_SERVER['DOCUMENT_ROOT'] . '/Nexus/BackEnd';

// ----- DatabaseManager, PDO & CentralController init
require_once $path . '/controllers/centralcontroller.php';
$centralController = CentralController::getInstance();

$database_manager = $centralController->database_manager;


// ----- Request URL & Routing
$requestUrl = $_GET['url'] ?? '';

// switch ($requestUrl) {
//     case 'users':
//         break;
//     case 'games':
//         break;
//     default:
//         printer("API endpoint not found");
//         break;
// }


// ----- Test zone; delete later.
$usersCtrl = $centralController->users_controller;
$user = $usersCtrl->getUserById(1);
printer($user, 'User fetch test');

// Token manager
$token_manager = $centralController->token_manager;


// Token is stored client-side with local storage, session storage, or cookies.
// TODO: Store token locally, and send token in the url header.
$tokens = $token_manager->generateTokens($user);
printer($tokens, 'Token generation test');


// TODO: Creating and reading backup of database as part of api launch routine.