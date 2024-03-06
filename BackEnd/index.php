<?php
//index.php

function printall($array)
{
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}



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

// Parse URL to separate the path from the query string
$urlComponents = parse_url($requestUri);
$path = $urlComponents['path'];
$queryString = $urlComponents['query'] ?? '';

// Optionally remove the base path or script name from the path
$scriptName = $_SERVER['SCRIPT_NAME'];
$basePath = str_replace('/index.php', '', $scriptName);
$uriPath = str_replace($basePath, '', $path);

$explodedURI = explode('/', trim($uriPath, '/'));
$table = $explodedURI[0] ?? null;
$crud_action = $explodedURI[1] ?? null;
$columName = $explodedURI[2] ?? null;
$value = $explodedURI[3] ?? null;

$includedColumns = isset($_GET['includedColumns']) ? explode(',', $_GET['includedColumns']) : null;
// $sorting = isset($_GET['sorting']) ? explode(',', $_GET['sorting']) : null;
if (isset($_GET['sorting'])) {
    $sortingParams = explode(',', $_GET['sorting']);
    $sorting = [];

    foreach ($sortingParams as $param) {
        list($key, $v) = explode(':', $param);
        $sorting[$key] = $v === 'true' ? true : false;
    }

    // Now $sorting is an associative array
    // echo "<br>Sorting Array: <pre>" . print_r($sorting, true) . "</pre><br>";
} else {
    $sorting = null; // Or any default value you wish
}



// Read the input data for POST, PUT, DELETE methods
$rawData = file_get_contents('php://input');
$decodedData = json_decode($rawData, true);
// print_r($rawData);

// console.log(" response : ", response.text());
// console.log(' response : ', response.text())
// Routing
switch ($method) {
    case 'GET':
        // print_r($explodedURI);
        // echo "<br> crud_action: " . $crud_action;
        // echo "<br>  sorting : " . print_r($sorting, true) . "<br>";
        // echo "<br>  sorting : " . $sorting["timestamp"] . "<br>";
        // echo "<br>  includedColumns : " . print_r($includedColumns, true);
        // echo "<br>  value : " . $value ."<br> " ;
       
        handleGet($table, $crud_action, $central_controller, $columName, $value, $includedColumns, $sorting);
        break;
    case 'POST':
    case "DELETE":
    case 'PUT':
        handlePost($table, $crud_action, $central_controller, $columName, $value, $includedColumns, $sorting, $decodedData);
        break;
    default:
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
}

// Function to handle GET requests
function handleGet($table, $crud_action, $centralController, $columName, $value, $includedColumns, $sorting)
{
    //Do these if they arent empty
    $controllerName = $table . '_controller';
    // $getAllFromTable = 'getAll_' . $table;
    // $getByColumnName = 'getBy' . $columName;
    //

    switch ($crud_action) {
        case 'getAll':
            // echo "<br>  includedColumns : " . $includedColumns;
            // echo "<br>  sorting : " . print_r($sorting, true) . "<br>";
            // echo "<br>  getAllFromTable : " . print_r($includedColumns, true);
            $result = $centralController->$controllerName->getAll($columName, $value, $includedColumns, $sorting);
            echo json_encode($result);
            break;
        case 'getOne':
            // echo "<br>  getByColumnName : " . $includedColumns;
            // print_r($includedColumns) ;
            $result = $centralController->$controllerName->getOne($columName, $value, $includedColumns);
            echo json_encode($result);
            break;
        default:
            echo json_encode(['error' => "Table $table Doesn't Exist"]);
            break;
    }
}


// Function to handle POST requests
function handlePost($table, $crud_action, $central_controller, $columName, $value, $includedColumns, $sorting, $decodedData)
{
    $controllerName = $table . '_controller';

    switch ($crud_action) {
        case 'logout':
        case 'create':
        case 'login':
        case 'getAllMatching':
        case 'insert':
        case 'update':
        case 'delete':
            // echo "<br>  getByColumnName : " . $decodedData;
            // print_r($decodedData);
            // print_r($decodedData);
            $result = handleRawData($central_controller, $decodedData, $controllerName, $crud_action);
            // $result = $centralController->$controllerName->$crud_action($value);
            echo json_encode($result);
            break;
        default:
            echo json_encode(['error' => "Action $crud_action is unnaplicable"]);
            break;
    }
}


function handleRawData($centralController, $decodedData, $controllerName, $crud_action)
{
    if (empty($decodedData)) {
        // echo "No data provided or data is empty.";
        // return false;
    } else {
        if (isset($decodedData['filters'])) {
            return handle_filterData($centralController, $decodedData, $controllerName, $crud_action);
        } else if (isset($decodedData['createData'])) {
            return handleCreate($centralController, $decodedData, $controllerName, $crud_action);
        } else if (isset($decodedData['deleteData'])) {
            return handleDelete($centralController, $decodedData, $controllerName, $crud_action);
        } else if (isset($decodedData['updateData'])) {
            return handleUpdate($centralController, $decodedData, $controllerName, $crud_action);
        } else if (isset($decodedData['login'])) {
            return handleLogin($centralController, $decodedData, $controllerName, $crud_action);
        } else if (isset($decodedData['logout'])) {
            return handleLogout($centralController, $decodedData, $controllerName, $crud_action);
        } else {
            echo "Invalid data structure.";
            // return false;
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
    $data = $decodedData['login'];
    $email = $data["email"];
    $pwd = $data["password"];
    // echo "<br>  crud_action : " . $crud_action ."<br>";
    // echo "Email Address: ", print_r($email, true), "<br>";
    // echo "Password: ", print_r($pwd, true), "<br>";
    return $centralController->$controllerName->$crud_action($email, $pwd);
}

function handleLogout($centralController, $decodedData, $controllerName, $crud_action)
{
    $data = $decodedData['logout'];
    $id =  $data['id'];
    $tokens = $data['tokens'];

    // echo "<br> logout data : <br>";
    // // return "logout test";
    // print_r($tokens);
    return $centralController->$controllerName->$crud_action($id, $tokens);
}


function handleCreate($centralController, $decodedData, $controllerName, $crud_action)
{
    $createData = $decodedData['createData'];

    // echo "<br> handleCreate  createData : ".  print_r($createData, true) ." <br>";
   
    return $centralController->$controllerName->$crud_action($createData);
}

function handleDelete($centralController, $decodedData, $controllerName, $crud_action)
{
    $data = $decodedData['deleteData'];

    // echo "<br> delete data : <br>";
    // print_r($data);
    return $centralController->$controllerName->$crud_action($data);
}

function handleUpdate($centralController, $decodedData, $controllerName, $crud_action)
{
    $data = $decodedData['updateData'];

    // echo "<br> update data : <br>";
    // print_r($data);
    return $centralController->$controllerName->$crud_action($data["id"], $data);
}
