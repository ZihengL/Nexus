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

$table = 'reviews';
$action = 'create';

$data = '{"credentials":{"id":12,"access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEyLCJpYXQiOjE3MTA1MTE0NjgsImV4cCI6MTcxMDUxNTA2OCwiaXNzIjoiTkVYVVMiLCJhdWQiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZC8ifQ.b1yVu68wDbz6tI_62PSUL1okggYcVn2juvTEZdwR8o8"},"request_data":{"userID":12,"gameID":"5","rating":2.5,"comment":"asdvgv"}}';
$jsonData = $data;
$decoded = json_decode($data, true);
printall($decoded);

// echo $decoded['credentials']['access_token'] === $b ? "ASDASDSADAAS" : "NO";


// Initialize a cURL session
printer(getURI($table, $action));

$ch = curl_init(getURI($table, $action));

// Set cURL options
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonData)
]);

// Execute the POST request
$response = curl_exec($ch);

// Close cURL session
curl_close($ch);

// Display the result
if ($response)
    printer($response);

printall($response);
