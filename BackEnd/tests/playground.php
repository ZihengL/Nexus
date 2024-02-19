<?php

global $path;
$path = $_SERVER['DOCUMENT_ROOT'] . '/Nexus/BackEnd/';
require_once $path . 'tests/temp_globals.php';

$users = $users_controller->getAll_users();

echo 'OLD<pre>';
print_r($users);
echo '</pre>';

echo 'NEW';
foreach ($users as $user) {
    $users_controller->update($user['id'], $user);
}

$new = $users_controller->getAll_users();
echo '<pre>';
print_r($new);
echo '</pre>';

$email = 'john.doe@example.com';
$password = 'password123';
// echo $email . ' ' . $password . '<br>';



echo $users_controller->login($email, $password);

$encrypted = password_hash($password, PASSWORD_DEFAULT);

echo 'other' . $password . ' ' . $encrypted . '<br>';

echo password_verify($password, $encrypted);
