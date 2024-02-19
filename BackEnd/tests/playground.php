<?php

global $path;
$path = $_SERVER['DOCUMENT_ROOT'] . '/Nexus/BackEnd/';
require_once $path . 'tests/temp_globals.php';

$users = $users_controller->getAll_users();

echo '<pre>';
print_r($users);
echo '</pre>';

foreach ($users as $user) {
    $oldPass = $user['password'];
    $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
}

$email = 'john.doe@example.com';
$password = 'password123';
echo $email . ' ' . $password . '<br>';

echo $users_controller->login($email, $password);
