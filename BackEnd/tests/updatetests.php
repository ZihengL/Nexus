<?php


global $path;
$path = $_SERVER['DOCUMENT_ROOT'] . '/Nexus/BackEnd';

require_once $path . '/controllers/central_Controller.php';

$centralController = CentralController::getInstance();
$databaseManager = $centralController->database_manager;


/*
FILTER ON RATINGS, NAMES, developperID
SORT ON DATE
And only recieve developperID
*/
// $filters = [
//     'ratingAverage' => ['gt' => 1, 'lte' => 7],
//     'name' => ['contain' => 'super'],
//     'developperID' => '4',
// ];
// $sorting = [
//     'releaseDate' => false  //most recent to oldes date
// ];
// $results_2 = $centralController->games_controller->applyFiltersAndSorting($filters, $sorting, null);
// echo "<pre>";
// echo "<br> <strong>testindex - filter ON RATINGS, NAMES(contain), developperID </strong> <br>\n";
// print_r($results_2);
// echo "<pre><br>";











