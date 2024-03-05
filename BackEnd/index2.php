<?php

/*******************************************************************/
/***************************** CONFIG ******************************/
/*******************************************************************/

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Return only the headers and not the content
    // Only allow CORS if we're doing a GET - this is a preflight request
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Access-Control-Allow-Credentials: true');
    exit;
}

header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');


/*******************************************************************/
/***************************** GLOBALS *****************************/
/*******************************************************************/

global $path;
global $baseURL;
global $central_controller;

$path = $_SERVER['DOCUMENT_ROOT'] . '/Nexus/BackEnd/';
require_once $path . '/controllers/central_controller.php';

$central_controller = CentralController::getInstance();


/*******************************************************************/
/***************************** PARSING *****************************/
/*******************************************************************/

// All requests are channeled through POSTs.
if ($_SERVER["REQUEST_METHOD"] !== 'POST')
    displayError('Unsupported method type.');


// PARSING URL & URI
$parsed_uri = [];

if (isset($_SERVER['REQUEST_URI'])) {
    $split_uri = explode('/', $_SERVER['REQUEST_URI']);
    $end_uri = array_pop($split_uri);
    parse_str($end_uri, $parsed_uri);
} else
    displayError('Invalid URI format.');


// PARSING TABLE & CRUD REQUEST
if (isset($parsed_uri['table']) && isset($parsed_uri['action'])) {
    ['table' => $table, 'action' => $action] = $parsed_uri;

    if ($controller = $central_controller->getTableController($table)) {
        if ($central_controller->validateCrudAction($action)) {
            $raw_data = file_get_contents('php://input');
            $decoded_data = json_decode($raw_data, true);

            echo json_encode($controller->parseRequest($action, $decoded_data));
        } else
            displayError("Action '$crud_action' is innaplicable");
    } else
        displayError("Table '$table' doesn't exist.");
} else
    displayError('Invalid URI format.');


/*******************************************************************/
/****************************** TOOLS ******************************/
/*******************************************************************/

function displayError($message)
{
    echo json_encode(['Error' => $message]);
}

function printall($item, $return = false)
{
    $element = "<pre>{print_r($item, true)}</pre><hr>";

    if (!$return)
        echo $element;

    return $element;
}
