<?php

// GLOBALS
global $path;
global $baseURL;
$path = $_SERVER['DOCUMENT_ROOT'] . '/Nexus/BackEnd/';


// IMPORTS
require_once $path . 'controllers/central_controller.php';
$central_controller = CentralController::getInstance();


// METHOD & URI
$method = $_SERVER["REQUEST_METHOD"];
$uri = $_SERVER["REQUEST_URI"];
$explodedURI = explode('/', $uri);
$endURI = end($explodedURI);

// Assuming the structure is /resource/action/id
$resource = $explodedURI[0] ?? null;
$action = $explodedURI[1] ?? null;
$id = $explodedURI[2] ?? null;

// Read the input data for POST, PUT, DELETE methods
$rawData = file_get_contents('php://input');
$decodedData = json_decode($rawData, true);

// Routing
switch ($method) {
    case 'GET':
        handleGet($resource, $action, $id, $centralController);
        break;
    case 'POST':
        handlePost($resource, $decodedData, $centralController);
        break;
    case 'PUT':
        handlePut($resource, $id, $decodedData, $centralController);
        break;
    case 'DELETE':
        handleDelete($resource, $id, $centralController);
        break;
    default:
        // Handle unsupported methods
        http_response_code(405); // Method Not Allowed
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
}

// Function to handle GET requests
function handleGet($resource, $action, $id, $centralController) {
    switch ($resource) {
        case 'games':
            if ($action === 'getAll') {
                $result = $centralController->games_controller->getAllProducts();
                echo json_encode($result);
            }
            // Add more cases as needed
            break;
        // Add more resources as needed
    }
}

// Add similar functions for POST, PUT, DELETE...
// handlePost($resource, $decodedData, $centralController) { ... }
// handlePut($resource, $id, $decodedData, $centralController) { ... }
// handleDelete($resource, $id, $centralController) { ... }

?>