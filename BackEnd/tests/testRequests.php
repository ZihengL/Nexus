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

// $data = '{"credentials":{"id":7,"access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiI3IiwiaWF0IjoxNzEwNDY5ODY1LCJleHAiOjE3MTA0NzM0NjUsImlzcyI6Ik5FWFVTIiwiYXVkIjoiaHR0cDovL2xvY2FsaG9zdDo0MjA4L05leHVzL0JhY2tFbmQvIn0.WP9AwVv6-KRe4PQ7sxCuniGXhK9TLt6Uf9PuueeicUc"},"request_data":{"userID":"7","gameID":"4","rating":2,"comment":"htjtkk"}}';
// $jsonData = $data;
// $decoded = json_decode($data, true);

// echo $decoded['credentials']['access_token'] === $b ? "ASDASDSADAAS" : "NO";


// Initialize a cURL session
printer(getURI($table, $action));
// printall($decoded);
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
echo $response;
