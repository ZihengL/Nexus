<?php

function printer($message)
{
    echo "<b>$message</b><br>";
}

function printall($item)
{
    echo '<pre>' . print_r($item, true) . '</pre>';
}

function getURI($table, $action)
{
    return 'http://localhost:4208/Nexus/BackEnd/' . "table=$table&action=$action";
}


$access_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEyLCJpYXQiOjE3MTA1MDc4ODQsImV4cCI6MTcxMDUxMTQ4NCwiaXNzIjoiTkVYVVMiLCJhdWQiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZC8ifQ.TsgnPk0ZwX8EHclSK23Ql8QjK3HLmiVjaffc3p0QlYM';
$id = 12;

$credentials = ['id' => $id, 'access_token' => $access_token];

$request_data = ['gameID' => 2, 'userID' => $id, 'rating' => 2.5, 'comment' => 'good'];

$data = ['credentials' => $credentials, 'request_data' => $request_data];
$jsonData = json_encode($data);


$uri = getURI($table, $action);


// $jsonData = $data;
// $decoded = json_decode($data, true);

// $token = $decoded['refresh_token'];

// printall($decoded);

$table = 'users';
$action = 'authenticate';
$uri = "http://localhost:4208/Nexus/Backend/table=$table&action=$action";
printer($uri);


$ch = curl_init($uri);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonData)
]);

$response = curl_exec($ch);
curl_close($ch);

printer("$action on $table " . ($response ? 'succeeded' : 'failed'));
printall($response);
