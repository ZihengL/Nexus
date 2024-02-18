<?php
//index.php



if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Return only the headers and not the content
    // Only allow CORS if we're doing a GET - this is a preflight request
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Access-Control-Allow-Credentials: true');
    exit;
}


header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Origin: http://localhost:3001');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');


// GLOBALS
global $path;
global $baseURL;
$path = $_SERVER['DOCUMENT_ROOT'] . '/Nexus/BackEnd/';

// IMPORTS
require_once $path . '/controllers/central_controller.php';
$central_controller = CentralController::getInstance();

// METHOD & URI
$method = $_SERVER["REQUEST_METHOD"];
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = $_SERVER['SCRIPT_NAME'];

// Optionally remove the base path or script name
$basePath = str_replace('/index.php', '', $scriptName);
$uriPath = str_replace($basePath, '', $requestUri);

$explodedURI = explode('/', trim($uriPath, '/'));
$table = $explodedURI[0] ?? null;
$crud_action = $explodedURI[1] ?? null;
$columName = $explodedURI[2] ?? null;
$value = $explodedURI[3] ?? null;

// Read the input data for POST, PUT, DELETE methods
$rawData = file_get_contents('php://input');
$decodedData = json_decode($rawData, true);

// console.log(" response : ", response.text());
// Routing
switch ($method) {
    case 'GET':
        // print_r($explodedURI);
        // echo "<br> The requested URI is: " . $value;
        handleGet($table, $crud_action, $central_controller, $columName, $value);
        break;
    case 'POST':
        handlePost($table, $crud_action, $central_controller, $columName, $value, $decodedData);
        break;
    case 'PUT':
        handlePut();
        break;
    case 'DELETE':
        handleDelete();
        break;
    default:
        // Handle unsupported methods
        http_response_code(405); // Method Not Allowed
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
}
           
// Function to handle GET requests
function handleGet($table, $crud_action, $centralController, $columName, $value)
{
    //Do these if they arent empty
    $controllerName = $table . '_controller';
    $getAllFromTable = 'getAll_' . $table;
    $getByColumnName = 'getBy' . $columName;
    //

    switch ($crud_action) {
        case 'getAll':
            // echo "<br>  getAllFromTable : " . $getAllFromTable;
            $result = $centralController->$controllerName->$getAllFromTable();
            echo json_encode($result);
            break;
        case 'getBy':
            // echo "<br>  getByColumnName : " . $getByColumnName;
            $result = $centralController->$controllerName->$getByColumnName($value);
            echo json_encode($result);
            break;
        default:
            http_response_code(405);
            echo json_encode(['error' => "Table $table Doesn't Exist"]);
            break;
    }
}


// Function to handle POST requests
function handlePost($table, $crud_action, $centralController, $columName, $value, $decodedData)
{
    $controllerName = $table . '_controller';

    switch ($crud_action) {
        // case 'login':
        //     handleLogin($centralController, $decodedData, $controllerName, $crud_action);
        //     break;
        // case 'logout':
        //     handleLogout($centralController, $decodedData, $controllerName, $crud_action);
        //     break;
        case 'login':
        case 'logout':
        case 'applyFiltersAndSorting':
            // echo "<br>  getByColumnName : " . $getByColumnName;
            $result = handleRawData($centralController, $decodedData, $controllerName, $crud_action);
            // $result = $centralController->$controllerName->$crud_action($value);
            echo json_encode($result);
            break;
        default:
            http_response_code(405);
            echo json_encode(['error' => "Action $crud_action is unnaplicable"]);
            break;
    }
}


function handleRawData($centralController, $decodedData, $controllerName, $crud_action)
{
    if (empty($decodedData)) {
        echo "No data provided or data is empty.";
        return false;
    } else {
        if (isset($decodedData['filters'])) {
          return handle_filterData($centralController, $decodedData, $controllerName, $crud_action);
        } else if (isset($decodedData['login'])){
            return handle_filterData($centralController, $decodedData, $controllerName, $crud_action);
        }else if (isset($decodedData['logout'])){

        }
        else {
            echo "Invalid data structure.";
            return false;
        }
    }
}


function handle_filterData($centralController, $decodedData, $controllerName, $crud_action)
{
    $filters = $decodedData['filters'];
    $sorting = isset($decodedData['sorting']) ? $decodedData['sorting'] : null;
    $includedColumns = isset($decodedData['includedColumns']) ? $decodedData['includedColumns'] : null;
    return $centralController->$controllerName->$crud_action($filters, $sorting, $includedColumns);
}

function handleLogin($centralController, $decodedData, $controllerName, $crud_action)
{
 
}

function handleLogout($centralController, $decodedData, $controllerName, $crud_action)
{
 
}


?>