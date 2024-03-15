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

$table = 'users';
$action = 'authenticate';
$uri = getURI($table, $action);

$data = '{"id":12,"refresh_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEyLCJpYXQiOjE3MTA1MTk1MTgsImV4cCI6MTcxMTEyNDMxOCwiaXNzIjoiTkVYVVMiLCJhdWQiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZC8ifQ.0YVC7kDJpJ5Z_m_WAsyRrLNX74A1AMleUtQwPISAzXI"}';
$jsonData = $data;
$decoded = json_decode($data, true);

$token = $decoded['refresh_token'];

printall($decoded);

// Initialize a cURL session
$uri = "http://localhost:4208/Nexus/Backend/table=$table&action=$action";
printer(getURI($table, $action));

$ch = curl_init($uri);

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
    printer("RESPONSE SUCCESS ");
else
    printer("RESPONSE FAILED ");

printall($response);
