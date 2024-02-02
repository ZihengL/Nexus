<?php

function printer($content, $index = 0, $title = 'ARRAY START')
{
    if (is_array($content)) {
        echo '<hr><h5>' . $title . '</h5><br>';

        $index = 0;
        if (array_keys($content) !== range(0, count($content) - 1)) {
            foreach ($content as $element => $value) {
                printer("$element: $value", $index);
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

printer(implode(' // ', $user), "implode test");

// $updated_user = $user;
// $updated_user['user'] = 'updated username';

// UPDATE user SET user = ?, password = ?, email = ?, phoneNumber = ?, picture = ?, IsAdmin = ?, IsOnline = ?, description = ?, name = ?, lastName = ?, creationDate = ? WHERE id = ?
// $usersCtrl->updateUser($user['id'], $updated_user);
// $result = $usersCtrl->getById($user['id']);
// printer($result, 'UPDATED USER RESULT');

// Base SQL before applying filters and sorting
$sql = "SELECT * FROM user";

// Define filters and sorting criteria
$filters = [
    'name' => "john_doe"
];
$sorting = [
    'phoneNumber' => 'ASC'
];

// Apply filters and sorting to the base SQL
$result = $centralController->usersController->applyFiltersAndSorting($sql, $filters, $sorting);

// TODO: Creating and reading backup of database as part of api launch routine.