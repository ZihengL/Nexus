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


// $data = '{"credentials":{"id":12,"access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEyLCJpYXQiOjE3MTA0ODI5NDUsImV4cCI6MTcxMDQ4NjU0NSwiaXNzIjoiTkVYVVMiLCJhdWQiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZC8ifQ.nCjg5JE-zYGtoZQm5j28xRiReuE3BgTvE7RJ2jbuu_U"},"request_data":{"userID":"12","gameID":"1","rating":2.5,"comment":"abcsadasd"}}';
// $jsonData = $data;
// $decoded = json_decode($data);
// printall($decoded);

$table = 'tags';
$action = 'update';


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
