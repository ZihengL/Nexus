<?php

function printer($content, $index = 0, $title = 'ARRAY START')
{
    if (is_array($content)) {
        echo '<hr><h5>' . $title . '</h5><br>';

        $index = 0;
        if (array_keys($content) !== range(0, count($content) - 1)) {
            foreach ($content as $element => $value) {
                $subcontent = "$element: <b>$value</b>";
                printer($subcontent, $index);
                $index++;
            }
        } else {
            foreach ($content as $element) {
                printer($element, $index);
                $index++;
            }
        }

        echo '<br>';
    } else {
        echo "$index - $content<br>";
    }
}

global $path;
$path = $_SERVER['DOCUMENT_ROOT'] . '/Nexus/BackEnd';

// ----- DatabaseManager, PDO & CentralController init
require_once $path . '/controllers/database.php';
require_once $path . '/controllers/centralController.php';

$databaseManager = DatabaseManager::getInstance();
$centralController = CentralController::getInstance();


// ----- Request URL & Routing
$requestUrl = $_GET['url'] ?? '';

switch ($requestUrl) {
    case 'users':
        break;
    case 'games':
        break;
    default:
        printer("API endpoint not found");
        break;
}


// ----- Test zone; delete later.
$usersCtrl = $centralController->usersController;
$user = $usersCtrl->getUserById(1);
printer($user, 0, 'USER FETCH TEST');



// TODO: Creating and reading backup of database as part of api launch routine.