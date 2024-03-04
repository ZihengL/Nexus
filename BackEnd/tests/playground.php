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

$joined_tables = [
    'users' => ['id', 'username'],
    'gamestags' => ['gameId', 'tagId']
];

/* JOINED_TABLES */
echo '<b>getOne</b><br>';
$result = $games_ctrl->getOne('title', 'Space Odyssey', null, $joined_tables);
printall($result);

echo '<b>getAllMatching</b><br>';
$result = $games_ctrl->getAllMatching(['title' => 'Space Odyssey'], null, null, $joined_tables);
printall($result);

echo 'With included tables<br>';
$result = $games_ctrl->getAllMatching(['title' => 'Space Odyssey'], null, ['id', 'developerID', 'title'], $joined_tables);
printall($result);

$games_ctrl->TEST();


/* URL DECODING */
$encoded = 'eyJmaWx0ZXJzIjp7InJhdGluZ0F2ZXJhZ2UiOiI1In0sInNvcnRpbmciOnsicmF0aW5nQXZlcmFnZSI6dHJ1ZX0sImluY2x1ZGVkQ29sdW1ucyI6WyJpZCIsImRldmVsb3BlcklEIiwidGFncyIsInJhdGluZ0F2ZXJhZ2UiXX0%3D';
$filtersJson = base64_decode(urldecode($encoded));

printall($filtersJson);

$filters = json_decode($filtersJson);

printall($filters);

echo '<b>END TEST</b><hr>';


/*******************************/

$arr1 = ['a' => 'tqifh', 'b' => 'kjahsdkjla', 'c' => 4231432, 'd' => 1];
$arr2 = ['c' => 213125, 'a'];
$arr3 = array_intersect_key($arr1, $arr2);

['a' => $a, 'c' => $c, 'd' => $d] = $arr1;

// echo $a . ' - ' . $c;














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
