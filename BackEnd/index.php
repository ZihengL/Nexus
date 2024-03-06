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
$path = $_SERVER['DOCUMENT_ROOT'] . '/Nexus/BackEnd/';

require_once "$path/controllers/central_controller.php";
$central_controller = CentralController::getInstance();


/*******************************************************************/
/***************************** REQUEST *****************************/
/*******************************************************************/

try {
    ['table' => $table, 'action' => $action] = parseURL();
    $request_method = $_SERVER['REQUEST_METHOD'];

    switch ($request_method) {
        case 'POST':
            $raw_data = file_get_contents('php://input');
            $data = json_decode($raw_data, true);
            break;
        case 'GET':
            $data = null;
            break;
        default:
            throw new Exception("Unsupported request method type '$request_method'.");
    }

    echo json_encode($central_controller->parseRequest($table, $action, $data));
} catch (Exception $e) {
    echo json_encode(['ERROR' => $e->getMessage()]);
}


/*******************************************************************/
/****************************** TOOLS ******************************/
/*******************************************************************/

function parseURL()
{
    $uri = $_SERVER['REQUEST_URI'];

    if (empty($uri))
        throw new Exception("Missing table and action parameters in request URI.");

    $split_uri = explode('/', $uri);
    $end_uri = array_pop($split_uri);
    parse_str($end_uri, $request_components);

    if (!isset($request_components['table']) || !isset($request_components['action']))
        throw new Exception("URI format '$end_uri' is innaplicable.");

    return $request_components;
}

function printall($item)
{
    echo '<pre>' . print_r($item, true) . '</pre>';
}
