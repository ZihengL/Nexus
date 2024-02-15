<?php

// function printer($content, $index = 0, $title = 'ARRAY START')
// {
//     if (is_array($content)) {
//         echo '<hr><h5>' . $title . '</h5><br>';

//         $index = 0;
//         if (array_keys($content) !== range(0, count($content) - 1)) {
//             foreach ($content as $element => $value) {
//                 printer("$element: $value", $index);
//                 $index++;
//             }
//         } else {
//             foreach ($content as $element) {
//                 printer($element, $index);
//                 $index++;
//             }
//         }

//         echo '<br>';
//     } else {
//         echo "$index - $content<br>";
//     }
// }

global $path;
$path = $_SERVER['DOCUMENT_ROOT'] . '/Nexus/BackEnd';

// ----- DatabaseManager, PDO & CentralController init
require_once $path . '/controllers/database_manager.php';
require_once $path . '/controllers/central_Controller.php';

$databaseManager = DatabaseManager::getInstance();
$centralController = CentralController::getInstance();


// // ----- Request URL & Routing
// $requestUrl = $_GET['url'] ?? '';

// switch ($requestUrl) {
//     case 'users':
//         break;
//     case 'games':
//         break;
//     default:
//         printer("API endpoint not found");
//         break;
// }


// // ----- Test zone; delete later.
// $usersCtrl = $centralController->usersController;
// $user = $usersCtrl->getUserById(1);
// printer($user, 0, 'USER FETCH TEST');

// printer(implode(' // ', $user), "implode test");

// $updated_user = $user;
// $updated_user['user'] = 'updated username';

// UPDATE user SET user = ?, password = ?, email = ?, phoneNumber = ?, picture = ?, IsAdmin = ?, IsOnline = ?, description = ?, name = ?, lastName = ?, creationDate = ? WHERE id = ?
// $usersCtrl->updateUser($user['id'], $updated_user);
// $result = $usersCtrl->getById($user['id']);
// printer($result, 'UPDATED USER RESULT');

                    //FILTER BY NON EXACT VALUES

//FILTER ON TAGS
$filters = [ 'tagId' => ['relatedTable' => 'gamesTags', 'values' => ['1', '3'], 'wantedColumn' => 'gameId']];
$results_1 = $centralController->gamesController->applyFiltersAndSorting($filters, null);
echo "<pre>";

echo "<br> <strong>testindex - tag results : </strong> <br>\n";
        print_r($results_1);
        echo "<pre><br>";

//FILTER ON RATINGS AND SORT ON RATINGS
$filters = ['ratingAverage' => ['gt' => 1, 'lte' => 5]];
$sorting = [
    'ratingAverage' => true
];

$results_2 = $centralController->gamesController->applyFiltersAndSorting($filters, $sorting);
echo "<pre>";
echo "<br> <strong>testindex - filter on ratings and sort on ratings results : </strong>  <br>\n";
        print_r($results_2);
        echo " <pre> <br>";


//FILTER ON NAMES
$filters = ['name' => 'Super Game'];
$results_2 = $centralController->gamesController->applyFiltersAndSorting($filters, null);
echo "<pre>";
echo "<br> <strong>testindex - exact name results :</strong>  <br>\n";
        print_r($results_2);
        echo "<pre><br>";


//FILTER ON RATINGS, NAMES, AND SORT ON DATE
$filters = [
    'ratingAverage' => ['gt' => 1, 'lte' => 7],
    'name' => ['contain' => 'super'],
    // 'developperID' => '4',
    ];
$sorting = [
    'releaseDate' => false
];

$results_2 = $centralController->gamesController->applyFiltersAndSorting($filters, $sorting);


echo "<br> <strong>testindex - filter ON RATINGS, NAMES(contain), AND SORT ON DATE results : </strong> <br>\n";
        echo "<pre>";   
        print_r($results_2);
        echo "<pre><br>";

                    
//FILTER ON RATINGS, NAMES, AND SORT ON DATE
$filters = [
    'ratingAverage' => ['gt' => 1, 'lte' => 7],
    'name' => ['contain' => 'super'],
    'developperID' => '4',
    ];
$sorting = [
    'releaseDate' => false  //most recent to oldes date
];

$results_2 = $centralController->gamesController->applyFiltersAndSorting($filters, $sorting);
echo "<pre>";
echo "<br> <strong>testindex - filter ON RATINGS, NAMES(contain), developperID AND SORT ON DATE results : </strong> <br>\n";
        print_r($results_2);
        echo "<pre><br>";
              