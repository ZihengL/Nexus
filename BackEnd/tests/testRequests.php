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

$access_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjExLCJpYXQiOjE3MTA0Nzg0NTcsImV4cCI6MTcxMDQ4MjA1NywiaXNzIjoiTkVYVVMiLCJhdWQiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZC8ifQ.1U7lIrNlQroeAD2Nq7MC_yXjCR9-A3oDKxN45AZftQ0';
$id = 11;

$credentials = ['id' => $id, 'access_token' => $access_token];
$request_data = ['id' => $id, 'username' => 'blahblahbwahjka'];

$data = ['credentials' => $credentials, 'request_data' => $request_data];
$data = ['id' => 17, 'name' => 'metroidvania'];
$jsonData = json_encode($data);


 $data = '{"credentials":{"id":7,"access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiI3IiwiaWF0IjoxNzEwNDY5ODY1LCJleHAiOjE3MTA0NzM0NjUsImlzcyI6Ik5FWFVTIiwiYXVkIjoiaHR0cDovL2xvY2FsaG9zdDo0MjA4L05leHVzL0JhY2tFbmQvIn0.WP9AwVv6-KRe4PQ7sxCuniGXhK9TLt6Uf9PuueeicUc"},"request_data":{"userID":"7","gameID":"4","rating":2,"comment":"htjtkk"}}';
$jsonData = $data;
$decoded = json_decode($data);
printall($decoded);

$table = 'reviews';
$action = 'create';


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
echo $response;
