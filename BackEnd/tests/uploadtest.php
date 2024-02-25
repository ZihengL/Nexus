<?php

// global $path;
// $path = $_SERVER['DOCUMENT_ROOT'] . '/Nexus/BackEnd/';

// require_once $path . 'controllers/central_controller.php';
// $central_controller = CentralController::getInstance();

// $users_ctrl = $central_controller->users_controller;

// $user = [
//     'password' => 'caca',
//     'username' => 'testuser',
//     'email' => 'caca@caca',
//     'name' => 'asdasdas',
//     'lastName' => 'abc'
// ];

// $response = $users_ctrl->create($user);

// echo $response;

global $path;
$path = $_SERVER['DOCUMENT_ROOT'] . '/Nexus/BackEnd/';

require_once $path . 'controllers/central_controller.php';
$central_controller = CentralController::getInstance();

$scriptpath = $path . 'tests/upload.js';

$client_manager = $central_controller->google_client_manager;

$filepath = 'Z:\Projects\Xampp\Nexus\BackEnd\tests';

$upload_url = $client_manager->createUploadSession($filepath);

?>


<script src=<?php echo $filepath ?> type="text/javascript">
    upload(<?php echo $upload_url ?>);
</script>