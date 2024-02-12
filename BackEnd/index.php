<?php

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Return only the headers and not the content
    // Only allow CORS if we're doing a GET - this is a preflight request
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Access-Control-Allow-Credentials: true');
    exit;
}


header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Origin: http://localhost:3001');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Global vars
global $path;
global $baseURL;
global $centralController;
$dbUsername = "nexus";
$dbPassword = "123";

$path = $_SERVER['DOCUMENT_ROOT'] . '/Nexus/BackEnd';
$baseURL = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/Nexus";

// $path = "$_SERVER[DOCUMENT_ROOT]/zi_htdocs/RichRicasso";
// $baseURL = $baseURL = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/zi_htdocs/RichRicasso";

// Pathing and URL
// require_once "$path/transactions/routines.php";
require_once "$path/controllers/database.php";
require_once "$path/controllers/centralcontroller.php";


// centralController
$centralController = CentralController::getInstance($dbUsername, $dbPassword);

// METHOD & URI
$method = $_SERVER["REQUEST_METHOD"];
$uri = $_SERVER["REQUEST_URI"];
$explodedURI = explode('/', $uri);
$endURI = end($explodedURI);

$result = null;

if ($method === 'GET') {
    switch ($endURI) {
        case 'getAllProducts':
            $result = $centralController->gamesController->getAllProducts();
            break;
        default:
            $result = [];
    }
} else {

    $rawData = file_get_contents('php://input');
    $decodedData = json_decode($rawData, true);

    switch ($endURI) {
        case 'filterGames':
            $result = $centralController->gamesController->filter($decodedData, []);
            break;
        case 'getGame':
            $result = $centralController->gamesController->getProductById($decodedData);
            break;
        case 'getUser':
            $result = $centralController->usersController->getUserById($decodedData);
            break;
        case 'updateUser':
            if (json_last_error() === JSON_ERROR_NONE) {
                $userId = $decodedData['id'];
                $password = $decodedData['password'];

                $result = $centralController->usersController->updateUser($userId, ['password' => $password]);
            }
            break;
        case 'login':
            if (json_last_error() === JSON_ERROR_NONE) {
                $email = $decodedData['email'];
                $password = $decodedData['password'];

                $result = $centralController->usersController->login($email, $password);
            }
            break;
        case 'logout':
            $centralController->usersController->logout();
            break;
        case 'register':
            $result = $centralController->usersController->createUser($decodedData);
            break;
        case 'deleteUser':
            $result = $centralController->usersController->deleteUsers($decodedData);
            break;
        case 'deleteGame':
            $result = $centralController->gamesController->deleteProduct($decodedData);
            break;
            // case 'check':
            //     require_once "$path/transactions/checkout.php";
            //     $centralController->addToCart('prod_PBRpRO0LQiFGuL', 2);

            //     $result = require_once "$path/transactions/checkout.php";
            //     break;
            // case 'create_payment_intent':
            //     $decodedData = json_decode(file_get_contents('php://input'), true);
            //     $amount = $decodedData['amount']; // amount should be sent from the client

            //     try {
            //         $paymentIntent = \Stripe\PaymentIntent::create([
            //             'amount' => $amount,
            //             'currency' => 'usd',
            //             // Add other payment intent configurations if needed
            //         ]);
            //         $result = ['clientSecret' => $paymentIntent->client_secret];
            //     } catch (\Stripe\Exception\ApiErrorException $e) {
            //         // Handle the exception
            //         $result = ['error' => $e->getMessage()];
            //     }
            //     break;
            // case 'checkout2':
            //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            //         require __DIR__ . '/vendor/autoload.php';
            //         // require_once 'vendor/autoload.php';
            //         \Stripe\Stripe::setApiKey('sk_test_51ONc07CVit0Y8pd9Dk5GcWhGU972X4gpcKHnrzFNzWsPRiKV1nkCWSgqgY2vzgSmOoKxMpyIvkWDAJa00FXkqTC000lqaHBgF6');

            //         $stripe_secret_key = "sk_test_51ONc07CVit0Y8pd9Dk5GcWhGU972X4gpcKHnrzFNzWsPRiKV1nkCWSgqgY2vzgSmOoKxMpyIvkWDAJa00FXkqTC000lqaHBgF6";

            //         // print("hello")
            //         \Stripe\Stripe::setApiKey($stripe_secret_key);

            //         $checkout_session = \Stripe\Checkout\Session::create([
            //             "mode" => "payment",
            //             "success_url" => "http://localhost/success.php",
            //             "cancel_url" => "http://localhost/index.php",
            //             "locale" => "auto",
            //             "line_items" => [
            //                 [
            //                     "quantity" => 1,
            //                     "price_data" => [
            //                         "currency" => "usd",
            //                         "unit_amount" => $decodedData['amount'],
            //                         "product_data" => [
            //                             "name" => "Rich Ricasso Clothes"
            //                         ]
            //                     ]
            //                 ]
            //             ]
            //         ]);

            //         http_response_code(303);
            //         // print("Location: " . $checkout_session->url);
            //         // echo header("Location: " . $checkout_session->url);
            //         echo json_encode(['checkoutUrl' => $checkout_session->url]);
            //         exit;
            //     }
            //     break;
        default:
            $result = [];
    }
}

if ($result) {
    echo json_encode($result);
}
