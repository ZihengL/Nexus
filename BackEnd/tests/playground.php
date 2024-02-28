<?php

// global $path;
// $path = $_SERVER['DOCUMENT_ROOT'] . '/Nexus/BackEnd/';
// require_once $path . 'tests/temp_globals.php';

// $users = $users_controller->getAll_users();

// echo 'OLD<pre>';
// print_r($users);
// echo '</pre>';

// echo 'NEW';
// foreach ($users as $user) {
//     $users_controller->update($user['id'], $user);
// }

// $new = $users_controller->getAll_users();
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

// echo $refresh . '</br>';

// $stored = $tokens_controller->getByHashcode($refresh);

// $tokens_controller->deleteAllFromUser(1990, ['refresh_token' => $refresh]);

// echo '<pre>';
// print_r($stored);
// echo '</pre>';

// $users_ctrl = $central_controller->users_controller;

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


$arr1 = ['a' => 'tqifh', 'b' => 'kjahsdkjla', 'c' => 4231432];
$arr2 = ['c' => 213125];
$arr3 = array_intersect_key($arr1, $arr2);

// print_r($arr3);
echo implode(", table.", array_keys($arr1));



// echo '<br>';
// print_r($arr2);
// echo '<br>';
// print_r(array_diff_key($arr1, $arr2));
