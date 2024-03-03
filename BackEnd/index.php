<?php
//index.php

function printall($item)
{
    echo '<pre>';
    print_r($item);
    echo '</pre><hr>';
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


// GLOBALS & IMPORTS
global $path;
global $baseURL;
global $central_controller;

$path = $_SERVER['DOCUMENT_ROOT'] . '/Nexus/BackEnd/';
require_once $path . '/controllers/central_controller.php';

$central_controller = CentralController::getInstance();

// METHOD & URI
$method = $_SERVER["REQUEST_METHOD"];
$requestUri = $_SERVER['REQUEST_URI'];

// PARSE URL
$url_components = parse_url($requestUri);
$path = $url_components['path'];
$query_string = $url_components['query'] ?? '';

// PARSE PATHING
$script_name = $_SERVER['SCRIPT_NAME'];
$base_path = str_replace('/index.php', '', $script_name);
$uri_path = str_replace($base_path, '', $path);
$exploded_URI = explode('/', trim($uri_path, '/'));

// PARSE PARAMS
$table = $exploded_URI[0] ?? null;
$crud_action = $exploded_URI[1] ?? null;
$column = $exploded_URI[2] ?? null;
$value = $exploded_URI[3] ?? null;


// $included_columns = isset($_GET['includedColumns']) ? explode(',', $_GET['includedColumns']) : [];
// $sorting = isset($_GET['sorting']) ? explode(',', $_GET['sorting']) : null;
$included_columns = explode(',', $_GET['includedColumns']) ?? [];
$sorting = [];
// if (isset($_GET['sorting']))
//     $sorting_params = explode(',', $_GET['sorting']);
if ($sorting_params = explode(',', $_GET['sorting']))
    foreach ($sorting_params as $param) {
        list($key, $v) = explode(':', $param);
        $sorting[$key] = $v === 'true' ? true : false;
    }

// Read the input data for POST, PUT, DELETE methods
$raw_data = file_get_contents('php://input');
$decoded_data = json_decode($raw_data, true);
// print_r($rawData);

// console.log(" response : ", response.text());
// console.log(' response : ', response.text())
// Routing
switch ($method) {
    case 'GET':
        // print_r($explodedURI);
        // echo "<br> crud_action: " . $crud_action;
        //   echo "<br>  sorting : " . print_r($sorting, true) . "<br>";
        //   echo "<br>  sorting : " . $sorting["timestamp"] . "<br>";
        // echo "<br>  includedColumns : " . print_r($includedColumns, true);
        // echo "<br>  value : " . $value ."<br> " ;
        handleGet($table, $crud_action, $column, $value, $included_columns, $sorting);
        break;
    case 'POST':
    case 'DELETE':
    case 'PUT':
        handlePost($table, $crud_action, $column, $value, $included_columns, $sorting, $decoded_data);
        break;
    default:
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
}


/*******************************************************************/
/***************************** HANDLERS ****************************/
/*******************************************************************/

function handleGet($table, $crud_action, $column, $value, $included_columns, $sorting)
{
    global $central_controller;
    //Do these if they arent empty
    // $getAllFromTable = 'getAll_' . $table;
    // $getByColumnName = 'getBy' . $columName;

    $controller_name = $table . '_controller';
    switch ($crud_action) {
        case 'getOne':
            $result = $central_controller->$controller_name->getOne($column, $value, $included_columns);
            echo json_encode($result);
            break;
        case 'getAll':
            $result = $central_controller->$controller_name->getAll($column, $value, $included_columns, $sorting);
            echo json_encode($result);
            break;
        default:
            echo json_encode(['error' => "Table $table Doesn't Exist"]);
            break;
    }
}

function handlePost($table, $crud_action, $column, $value, $included_columns, $sorting, $decoded_data)
{
    global $central_controller;

    $controller_name = $table . '_controller';
    switch ($crud_action) {
        case 'getAllMatching':
        case 'login':
        case 'logout':
        case 'create':
        case 'insert':
        case 'update':
        case 'delete':
            $result = handleRawData($decoded_data, $controller_name, $crud_action);
            echo json_encode($result);
            break;
        default:
            echo json_encode(['error' => "Action $crud_action is unnaplicable"]);
            break;
    }

    // $valid_actions = ['getAllMatching', 'login', 'logout', 'create', 'insert', 'update', 'delete'];

    // $result = ['error' => "Action $crud_action is unnaplicable"];
    // if (in_array($crud_action, $valid_actions))
    //     $result = handleRawData($decoded_data, $controller_name, $crud_action);

    // echo json_encode($result);
}

function handleRawData($decoded_data, $controller_name, $crud_action)
{
    if (empty($decoded_data)) {
        // echo "No data provided or data is empty.";
        // return false;
    } else {
        if (isset($decoded_data['filters'])) {
            return handleFilterData($decoded_data, $controller_name, $crud_action);
        } else if (isset($decoded_data['createData'])) {
            return handleCreate($decoded_data, $controller_name, $crud_action);
        } else if (isset($decoded_data['deleteData'])) {
            return handleDelete($decoded_data, $controller_name, $crud_action);
        } else if (isset($decoded_data['updateData'])) {
            return handleUpdate($decoded_data, $controller_name, $crud_action);
        } else if (isset($decoded_data['login'])) {
            return handleLogin($decoded_data, $controller_name, $crud_action);
        } else if (isset($decoded_data['logout'])) {
            return handleLogout($decoded_data, $controller_name, $crud_action);
        } else {
            echo "Invalid data structure.";
        }
    }

    // $action_map = [
    //     'filters'    => 'handleFilterData',
    //     'createData' => 'handleCreate',
    //     'deleteData' => 'handleDelete',
    //     'updateData' => 'handleUpdate',
    //     'login'      => 'handleLogin',
    //     'logout'     => 'handleLogout',
    // ];

    // foreach ($action_map as $request => $method)
    //     if (isset($decoded_data[$request]))
    //         return $method($decoded_data, $controller_name, $crud_action);

    // echo 'Invalid data structure';
}

function handleFilterData($decoded_data, $controller_name, $crud_action)
{
    global $central_controller;

    $filters = $decoded_data['filters'];
    $sorting = $decoded_data['sorting'] ?? [];
    $included_columns = $decoded_data['includedColumns'] ?? [];
    // $sorting = isset($decoded_data['sorting']) ? $decoded_data['sorting'] : [];
    // $included_columns = isset($decoded_data['includedColumns']) ? $decoded_data['includedColumns'] : [];
    return $central_controller->$controller_name->$crud_action($filters, $sorting, $included_columns);
}


/*******************************************************************/
/**************************** VALIDATION ***************************/
/*******************************************************************/

function handleLogin($decoded_data, $controller_name, $crud_action)
{
    global $central_controller;
    // $data = $decoded_data['login'];
    // $email = $data["email"];
    // $password = $data["password"];

    ['email' => $email, 'password' => $password] = $decoded_data['login'];
    return $central_controller->$controller_name->$crud_action($email, $password);
}

function handleLogout($decoded_data, $controllerName, $crud_action)
{
    global $central_controller;
    // $data = $decoded_data['logout'];
    // $id =  $data['id'];
    // $tokens = $data['tokens'];

    ['logout' => $data, 'id' => $id, 'tokens' => $tokens] = $decoded_data['logout'];
    return $central_controller->$controllerName->$crud_action($id, $tokens);
}


/*******************************************************************/
/****************************** CRUDS ******************************/
/*******************************************************************/

function handleCreate($decoded_data, $controller_name, $crud_action)
{
    global $central_controller;

    $data = $decoded_data['createData'];
    return $central_controller->$controller_name->$crud_action($data);
}

function handleDelete($decoded_data, $controller_name, $crud_action)
{
    global $central_controller;

    $data = $decoded_data['deleteData'];
    return $central_controller->$controller_name->$crud_action($data);
}

function handleUpdate($decoded_data, $controller_name, $crud_action)
{
    global $central_controller;

    $data = $decoded_data['updateData'];
    return $central_controller->$controller_name->$crud_action($data["id"], $data);
}
