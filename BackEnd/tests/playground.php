<?php

function explodetest($a)
{
    echo "TEST ";
    return explode(', ', $a) ?? [];
}

/*******************************/

global $path;
$path = $_SERVER['DOCUMENT_ROOT'] . '/Nexus/BackEnd/';
require_once $path . 'tests/temp_globals.php';
require_once $path . 'models/base_model.php';

$users_ctrl = $central_controller->users_controller;
$games_ctrl = $central_controller->games_controller;
$gametags_ctrl = $central_controller->gamestags_controller;

/*******************************/

BaseModel::$print_queries = true;

echo '<hr><b>BEGIN TEST</b><hr>';

// JOINED_TABLES
// $joined_tables = [
//     'users' => ['id', 'username'],
//     'gamestags' => ['gameId', 'tagId']
// ];

// echo '<b>getOne</b><br>';
// $result = $games_ctrl->getOne('title', 'Space Odyssey', null, $joined_tables);
// printall($result);

// echo '<b>getAllMatching</b><br>';
// $result = $games_ctrl->getAllMatching(['title' => 'Space Odyssey'], null, null, $joined_tables);
// printall($result);

// echo 'With included tables<br>';
// $result = $games_ctrl->getAllMatching(['title' => 'Space Odyssey'], null, ['id', 'developerID', 'title'], $joined_tables);
// printall($result);

// $games_ctrl->TEST();


// URL DECODING
// try {
//     // $encoded = $_GET['action'];
//     $encodedData = 'MTIwLDE1NiwyMDMsNDMsMjA1LDIwMSwxLDAsNCw5NSwxLDE4OA==';

//     $compressedData = base64_decode($encodedData); // Base64 decode

//     // Decompress data
//     $decompressedData = gzuncompress($compressedData);

//     if ($decompressedData === FALSE) {
//         // Handle decompression error
//         die('Decompression failed');
//     } else {
//         // Convert JSON string back to array
//         $dataArray = json_decode($decompressedData, true);

//         // Now, use $dataArray as needed
//         var_dump($dataArray);
//     }

//     $decodedURL = urldecode($encoded);
//     echo "DECODED URL: $decodedURL<br>";

//     $filtersJson = gzinflate(substr(urldecode($encoded), 2, -4));

//     // printall($filtersJson);

//     $filters = json_decode($filtersJson, true);

//     printall($filters);
// } catch (Exception $e) {
//     printall($e->getMessage());
// }


$arr1 = ['a' => 'tqifh', 'b' => 'kjahsdkjla', 'c' => 4231432, 'd' => 1];
$arr2 = ['c' => 213125, 'a'];
$arr3 = array_intersect_key($arr1, $arr2);

['a' => $a, 'c' => $c, 'd' => $d] = $arr1;

// echo $a . ' - ' . $c;

$url = 'https://www.php.net/manual/en/function.parse-url.php';
$url = 'http://localhost:4208/Nexus/BackEnd/table=users&action=getAll';
$url = 'http://localhost:4208/Nexus/BackEnd/table=users&asdasda=getAll';

$url_components = parse_url($url);
printall($url_components);

$split_uri = explode('/', $url_components['path']);
$end_uri = array_pop($split_uri);

parse_str($end_uri, $parsed_uri);
$table = $parsed_uri['table'] ?? null;
$action = $parsed_uri['action'] ?? null;

if ($controller = $central_controller->getTableController($table)) {
    echo 'good';
} else {

}

if ($controller = $central_controller->getTableController($table)) {
    echo 'good';
} else {
    echo 'bad';
}

echo '<br><b>END TEST</b><hr>';


/*******************************/













/*******************************/

// echo 'OLD<pre>';
// print_r($users);
// echo '</pre>';

// echo 'NEW';
// foreach ($users as $user) {
//     $users_controller->update($user['id'], $user);
// }

// echo '<pre>';
// print_r($new);
// echo '</pre>';

// $email = 'john.doe@example.com';
// $password = 'password123';

// $login_tokens = $users_controller->login($email, $password);

// printall($login_tokens);

// $logout = $users_controller->logout($login_tokens);

// printall($logout);

// $arr = ['a' => 1, 'b' => 2, 'c' => 5];

// printall($arr);

// $arr = array_diff_key($arr, ['b', 'c']);

// printall($arr);

// $originalArray = [
//     'a' => 'Apple',
//     'b' => 'Banana',
//     'c' => 'Cherry',
//     'd' => 'Dragonfruit'
// ];

// $client_manager = $central_controller->google_client_manager;

// $drive_controller = $client_manager->drive_controller;

// $name = $drive_controller->createUserSubfolder(29);

// echo $name;

// $tokens_controller = $central_controller->tokens_controller;

// $refresh = $tokens_controller->generateRefreshToken(1990);

// echo $refresh . '</hr>';

// $stored = $tokens_controller->getByHashcode($refresh);

// $tokens_controller->deleteAllFromUser(1990, ['refresh_token' => $refresh]);

// echo '<pre>';
// print_r($stored);
// echo '</pre>';



// $response = $users_ctrl->getAll();

// echo '<hr>USERS<br>';
// foreach ($response as $user) {
//     echo '<pre>';
//     echo print_r($user);
//     echo '</pre><br>';
// }

// $games_ctrl = $central_controller->games_controller;

// $response = $games_ctrl->getAllMatching(['title' => 'Fantastic Adventure'], null, ['title']);

// echo '<hr>GAMES<br>';
// foreach ($response as $game) {
//     echo '<pre>';
//     echo print_r($game);
//     echo '</pre><br>';
// }

// $keys = $games_ctrl->model->keys;

// printall($users_ctrl->);

// $refs = [
//     ['COLUMN_NAME'] => 'developerID', ['REFERENCED_TABLE_NAME'] => 'users', ['REFERENCED_COLUMN_NAME'] => 'id',
//     ['COLUMN_NAME'] => 'caca', ['REFERENCED_TABLE_NAME'] => 'ba', ['REFERENCED_COLUMN_NAME'] => 'lol'
// ];

// $joined = ['developerID' => '2'];

// foreach ($refs as $ref) {
//     printall($ref);
//     // $intersect = array_intersect_key($ref['COLUMN_NAME'], $joined);
// }
// echo '<br>';
// print_r($arr2);
// echo '<br>';
// print_r(array_diff_key($arr1, $arr2));
