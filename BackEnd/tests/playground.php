<?php

function getFromData($keys, $data, $unset = true)
{
    $items = [];

    foreach ($keys as $key)
        if (isset($data[$key])) {
            $items[] = $data[$key];

            if ($unset)
                unset($data[$key]);
        } else {
            echo '<br>' . $key . '<br>';
            printall($data);
            // throw new Exception("Missing $key parameter for request in data: " . unwrap($data));
        }

    return [...$items, $data];
}

function getOneFromData($key, $data, $unset = false)
{
    if (isset($data[$key])) {
        $item = $data[$key];

        if ($unset) {
            unset($data[$key]);
            return [$item, $data];
        }

        return $item;
    }

    throw new Exception("Missing '$key' parameter for request in data: " . unwrap($data));
}

function unwrap($item)
{
    return var_export($item, true) ?? '';
}

function printall($item)
{
    echo '<pre>' . print_r($item, true) . '</pre>';
}

function areSet($array, $keys)
{
    foreach ($keys as $key)
        if (!isset($array[$key]))
            return false;

    return true;
}



/*******************************/

global $path;
$path = $_SERVER['DOCUMENT_ROOT'] . '/Nexus/BackEnd/';
// require_once $path . 'tests/temp_globals.php';
require_once $path . 'models/base_model.php';

require_once $path . 'controllers/central_controller.php';
$central_controller = CentralController::getInstance();

$users_ctrl = $central_controller->users_controller;
$games_ctrl = $central_controller->games_controller;
$gamestags_ctrl = $central_controller->gamestags_controller;
$tokens_ctrl = $central_controller->tokens_controller;
$tags_ctrl = $central_controller->tags_controller;

$cc = $central_controller;

/*******************************/

// BaseModel::$print_queries = true;

function parse($table, $action, $data)
{
    $cc = CentralController::getInstance();

    return $cc->parseRequest($table, $action, $data);
}

echo '<hr><b>BEGIN TEST</b><hr>';

$at = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEwLCJpYXQiOjE3MDk5Mzc0NDcsImV4cCI6MTcwOTk0MTA0NywiaXNzIjoiTkVYVVMiLCJhdWQiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZC8ifQ.LdKdhOazveam73aZ_p5yGmvIuDcKWSxk-DntT7AJRZQ';
$rt = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEwLCJpYXQiOjE3MDk5Mzc0NDcsImV4cCI6MTcxMDU0MjI0NywiaXNzIjoiTkVYVVMiLCJhdWQiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZC8ifQ.nmZCM5gCZkdCaSnnYS-M6kUdZua6FsLtGoOA2zKXr44';
$tks = ['access_token' => $at, 'refresh_token' => $rt];
$id = 10;



// $credentials = ['id' => $id, 'tokens' => $tks];

// [$id, $tokens, $data] = getFromData(['id', 'tokens'], $credentials, false);

// printall($id);
// printall($tokens);
// printall($data);

// [$id, $tokens, $data] = getFromData(['id', 'tokens'], $credentials, true);

// echo $id;
// printall($tokens);
// printall($data);


// $central_controller->parseRequest('users', 'create', ['email' => 'test@test', 'password' => '123']);

// $login = ['email' => 'test@test', 'password' => '123'];
// $result = $cc->parseRequest('users', 'login', ['email' => 'test@test', 'password' => '123']);

// $user = $result['user'];
// $tokens = $result['tokens'];
// printall($result);

// $credentials = ['id' => $user['id'], 'tokens' => $tokens];
// $request_data = ['donatorID' => $user['id'], 'donateeID' => 2];

// $result = parse('transactions', 'getLink', ['credentials' => $credentials, 'request_data' => $request_data]);

// printall($result);

// $data =  ['filters' => ['id' => 2], 'paging' => ['limit' => 3, 'offset' => 2]];

// $result = ['filters' => $filters, 'sorting' => $sorting, 'paging' => $paging] = $data + [[], [], []];

// printall($filters);
// printall($sorting);
// printall($paging);

// $result = parse('games', 'getAll', ['paging' => ['limit' => 4, 'offset' => 0], 'joined_tables' => ['users' => ['username', 'id', 'email', 'password']]]);

// $data = ['included_columns' => ['username', 'email'], 'paging' => ['limit' => 4, 'offset' => 0], 'joined_tables' => ['games' => []]];
// $result = parse('users', 'getAll', $data);

////////////////////////////////

// echo "<hr><h4>GAMES WITH THEIR TAGS N USERS</h4>";
// $data = [
//     // 'paging' => ['limit' => 4, 'offset' => 0],
//     'joined_tables' => ['tags' => ['id', 'name'], 'users' => ['id', 'username', 'picture', 'name']]
// ];

// $result = parse('games', 'getAll', $data);
// printall($result);

// echo "<hr><h4>USERS WITH THEIR GAMES</h4>";
// $data = [
//     // 'paging' => ['limit' => 4, 'offset' => 0],
//     'joined_tables' => ['games' => ['id', 'title', 'ratingAverage']]
// ];

// $result = parse('users', 'getAll', $data);
// printall($result);

// echo "<hr><h4>USERS WITH THEIR GAMES N TAGS</h4>";
// $data = [
//     // 'paging' => ['limit' => 4, 'offset' => 0],
//     'joined_tables' => ['games' => ['id', 'title', 'ratingAverage'], 'tags' => []]
// ];

// $result = parse('users', 'getAll', $data);
// printall($result);

// echo "<hr><h4>GAME WITH DEVELOPER, REVIEWS, AND TAGS</h4>";
// $data = [
//     // 'paging' => ['limit' => 4, 'offset' => 0],
//     'column' => 'id',
//     'value' => 2,
//     'joined_tables' => ['users' => ['id', 'username'], 'reviews' => [], 'tags' => []]
// ];

// $result = parse('games', 'getOne', $data);
// printall($result);

// echo "<hr><h4>GAME WITH DEVELOPER, REVIEWS, AND TAGS</h4>";
// $data = [
//     // 'paging' => ['limit' => 4, 'offset' => 0],
//     'column' => 'id',
//     'value' => 2,
//     'joined_tables' => ['users' => ['id', 'username'], 'reviews' => [], 'tags' => []]
// ];

// $result = parse('games', 'getAllMatching', $data);
// printall($result);




// echo "<hr>count test<br>";

// $result = parse('users', 'countAll', []);
// printall($result);

// $result = parse('users', 'countAllMatching', []);
// printall($result);


// $result = parse('games', 'getAll', ['column' => null, 'value' => null]);
// printall($result);

$result = parse('games', 'getAll', ['joined_tables' => [ 'users' => ['id', 'username', 'picture', 'isOnline'], 'tags' => ['id', 'name']]]);
printall($result);

$joins = ['joined_tables' => [ 'games' => ['id', 'title', 'releaseDate', 'ratingAverage']]];

$result = parse('users', 'getOne', ['column' => 'id', 'value' => 11, 'joined_tables' => [ 'games' => ['id', 'title', 'releaseDate', 'ratingAverage']]]);
printall($result);

// printall($gamestags_ctrl->getGamesWith(['users' => true, 'tags' => true]));



// $data = [
//     'included_columns' => ['id', 'username'],
//     // 'paging' => ['limit' => 4, 'offset' => 0],
//     'joined_tables' => ['games' => ['id', 'title']]
// ];
// $result = parse('', 'getAll', $data);

// printall($result);

// if ($games_ctrl->isCompWith('tags'))
//     echo 'composite with tags';
// else
//     echo 'not comp';

// if ($tags_ctrl->create(['name' => 'testtag2', 'gameId' => 3]))
//     echo 'created';

// $result = parse('games', '')

// $credentials = ['id' => $user['id'], 'tokens' => $tokens];
// echo parse('users', 'logout', $credentials) ? 'success' : 'fail';


$donatorID = 2;
$donateeID = 5;

// $result = parse('transactions', 'getLink', ['donatorID' => 2, 'donateeID' => 5]);

// printall($result);







// $user = $central_controller->parseRequest('users', 'login', ['email' => 'test@test', 'password' => '123']);

// printall($result);

// printall($gamestags_ctrl->getGamesWith(true, true));

// $createData = [
//     'username' => 'testUser123',
//     'password' => '123',
//     'email' => 'testUser@email',
// ];

// echo $users_ctrl->create($createData);

// $tokens = $users_ctrl->login('testUser@email', '123');

// printall($tokens);


// JOINED_TABLES
// $joined_tables = [
//     'users' => ['id', 'username'],
//     'gamestags' => ['gameId', 'tagId']
// ];

// echo '<b>getOne</b><br>';
// $result = $games_ctrl->getOne('title', 'Space Odyssey', null, $joined_tables);
// printall($result);

// echo '<b>getAllMatching</b><br>';
// $result = $games_ctrl->getAllMatching(['title' => 'Space Odyssey'], null, null, $joined_tables);
// printall($result);

// echo 'With included tables<br>';
// $result = $games_ctrl->getAllMatching(['title' => 'Space Odyssey'], null, ['id', 'developerID', 'title'], $joined_tables);
// printall($result);

// $games_ctrl->TEST();


// URL DECODING
// try {
//     // $encoded = $_GET['action'];
//     $encodedData = 'MTIwLDE1NiwyMDMsNDMsMjA1LDIwMSwxLDAsNCw5NSwxLDE4OA==';

//     $compressedData = base64_decode($encodedData); // Base64 decode

//     // Decompress data
//     $decompressedData = gzuncompress($compressedData);

//     if ($decompressedData === FALSE) {
//         // Handle decompression error
//         die('Decompression failed');
//     } else {
//         // Convert JSON string back to array
//         $dataArray = json_decode($decompressedData, true);

//         // Now, use $dataArray as needed
//         var_dump($dataArray);
//     }

//     $decodedURL = urldecode($encoded);
//     echo "DECODED URL: $decodedURL<br>";

//     $filtersJson = gzinflate(substr(urldecode($encoded), 2, -4));

//     // printall($filtersJson);

//     $filters = json_decode($filtersJson, true);

//     printall($filters);
// } catch (Exception $e) {
//     printall($e->getMessage());
// }


// $arr1 = ['a' => 'tqifh', 'b' => 'kjahsdkjla', 'c' => 4231432, 'd' => 1];
// $arr2 = ['c' => 213125, 'a'];
// $arr3 = array_intersect_key($arr1, $arr2);

// $a1 = ['a' => [1, 2, 3], 'b', 'c'];
// $a2 = ['a' => [4], 'b' => 2, 'd'];

// foreach ($a1 as $key => $value) {
//     $a3 = array_merge($a1, $a2);
// }


// if (!$arr['z'])
//     echo 'asdsadas';
// else
//     echo 'nah';

// if (array_key_exists('A', $a1))
//     echo 'exist';
// else
//     echo 'no';


// echo print_r($a3, true) . '<br>';
// $a2 = [...$a1, 'd', 'e'];

// $a = 3;
// $b = 'asdas';
// $c = 23423;

// $arr = ['b' => $b, 'c' => $c, 'a' => $a];
// $method = 'explodetest';

// echo $method(...$arr);

// printall($a2);



// ['a' => $a, 'c' => $c, 'd' => $d] = $arr1;



// echo $a . ' - ' . $c;

// $url = 'https://www.php.net/manual/en/function.parse-url.php';
// $url = 'http://localhost:4208/Nexus/BackEnd/table=users&action=getAll';
// $url = 'http://localhost:4208/Nexus/BackEnd/table=users&asdasda=getAll';

// $url_components = parse_url($url);
// printall($url_components);

// $split_uri = explode('/', $url_components['path']);
// $end_uri = array_pop($split_uri);

// parse_str($end_uri, $parsed_uri);
// $table = $parsed_uri['table'] ?? null;
// $action = $parsed_uri['action'] ?? null;

// $email = 'testUser@email';
// $password = '123';

// ['user' => $user, 'tokens' => $tokens] = $users_ctrl->login($email, $password);

// printall($user);

// // $tokens = ['access_token' => $access, 'refresh_token' => $refresh];
// // $res = $users_ctrl->login('testUser@email', '123');

// $updatedata = ['id' => 9, 'tokens' => $tokens, 'data' => ['username' => 'playgroundtest']];
// $res = $users_ctrl->update(...$updatedata);

// echo '<br>UPDATE';
// printall($res);

echo '<br><b>END TEST</b><hr>';


/*******************************/


//


// function explodetest($a, $b, $c, $d = 5)
// {
//     echo "TEST ";
//     echo $a . $b;
//     echo " $c $d";
// }

// // stuff between $a and $c will result in error without destructuring param arg
// function datatest($a, $z = null, ...$arr)
// {
//     echo print_r($arr) . '<br>';
//     if (!$z)
//         $z = 'abqwea';

//     return [...func_get_args(), $arr];
// }


// function testz($a, $arr, $z, ...$arr1)
// {
//     printall($arr1);
//     echo $z;

// }

// $arr1 = ['a' => 'a val', 'b' => 'b val', 'c' => 'c val', 'd' => 'd val', 'z' => 'z val'];

// $arr2 = ['asdsadas', 'agweqwtn'];

// // printall([...$arr1, $arr2]);


// // testz(...$arr1);

// // Don't invoke one param functions with destructuring
// function datatest2($arr)
// {
//     print_r($arr);
// }

// function datatest3(...$arr)
// {
//     print_r(...$arr);
// }

// function datatest4($a, $b = null)
// {
//     echo $a . ' ' . $b;
// }

// $arr1 = ['a' => 'a val', 'b' => 'b val', 'c' => 'c val', 'd' => 'd val', 'z' => 'z val'];


// $ac = $arr1['z'] ??= 2;
// echo 'AC ' . $arr1['z'];
// printall($arr1);

// ['z' = $z, 'a' = $a] = $arr1 ??= ['def'];


// $arr2 = datatest(...$arr1);
// print_r($arr2);
// // datatest2(...$arr1);
// datatest3(...$arr1);
// datatest4(...$arr1);

// function datatest5($email = array('email'))
// {
//     echo 'datatest5<br>';
//     echo $email . '<br><br>';
// }

// $arr = ['a' => 'asdsadas', 'email' => 'a@a', 'asdasdas' => 'fsafasfa'];

// datatest5($arr);

// function makecoffee($types = array("cappuccino"), $coffeeMaker = NULL)
// {
//     $device = is_null($coffeeMaker) ? "hands" : $coffeeMaker;
//     return "Making a cup of " . join(", ", $types) . " with $device.\n";
// }
// echo makecoffee();
// echo makecoffee(array("cappuccino", "lavazza"), "teapot");










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
