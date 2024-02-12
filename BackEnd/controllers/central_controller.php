<?php

require_once "$path/controllers/database_manager.php";
require_once "$path/controllers/games_controller.php";
require_once "$path/controllers/users_controller.php";
require_once "$path/controllers/tokens_controller.php";

require_once "$path/remote/routines.php";

use Dotenv\Dotenv as Dotenv;


class CentralController
{
    private static $instance = null;

    public $database_manager;
    public $token_manager;
    public $routines_controller;

    public $users_controller;
    public $games_controller;

    // CONSTRUCTOR

    private function __construct()
    {
        global $path;
        $dotenv = Dotenv::createImmutable($path);
        $dotenv->load();

        // $this->database_manager = $this->instanciateDatabaseManager();
        $this->database_manager = DatabaseManager::getInstance();

        $pdo = $this->database_manager->getPDO();
        $this->token_manager = TokensController::getInstance($pdo);
        $this->users_controller = new UsersController($pdo, $this->token_manager);
        $this->games_controller = new GamesController($pdo);
        // $this->routines_controller = $this->instanciateRoutines();
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new CentralController();
        }

        return self::$instance;
    }

    // CONTROLLERS & MANAGERS

    // private function instanciateTokensController($pdo)
    // {
    //     $env = new stdClass();
    //     $env->access_key = $_ENV['JWT_ACCESS_KEY'];
    //     $env->refresh_key = $_ENV['JWT_REFRESH_KEY'];
    //     $env->algorithm = $_ENV['JWT_ALGORITHM'];
    //     $env->issuer = $_ENV['JWT_ISSUER'];
    //     $env->audience = $_ENV['JWT_AUDIENCE'];

    //     return TokensController::getInstance($pdo, $env);
    // }

    // private function instanciateDatabaseManager()
    // {
    //     $env = new stdClass();
    //     $env->host = $_ENV['DB_HOST'];
    //     $env->database = $_ENV['DB_NAME'];
    //     $env->username = $_ENV['DB_USER'];
    //     $env->password = $_ENV['DB_PASS'];

    //     return DatabaseManager::getInstance($env);
    // }

    // private function instanciateRoutines()
    // {
    //     $config = new stdClass();
    //     $config->run = true;
    //     $config->central_controller = $this;

    //     return Routines::getInstance($config);
    // }

    // GETTERS

    public function getUsersController()
    {
        return $this->users_controller;
    }

    public function getGamesController()
    {
        return $this->games_controller;
    }

    // COMMANDS

    public function setRoutines($toRunning)
    {
        $this->routines_controller->setRunningState($toRunning);
    }

    // // USER

    // public function login($email, $password) {
    //     return $this->usersController->login($email, $password);
    // }

    // public function logout() {
    //     $this->usersController->logout();
    // }

    // public function isAuthenticated() {
    //     return $this->usersController->isAuthenticated();
    // }

    // // NEWSLETTERS

    // public function userIsSubscribed() {
    //     if ($this->usersController->isAuthenticated()) {
    //         return $this->newslettersController->getSubscriberByEmail($_SESSION['user']['email']);
    //     }

    //     return false;
    // }

    // public function subscribeUser() {
    //     if ($this->usersController->isAuthenticated()) {
    //         return $this->newslettersController->getSubscriberByEmail($_SESSION['user']['email']);
    //     }

    //     return false;
    // }

    // // PRODUCTS

    // public function getProducts($ids = []) {
    //     $products = [];

    //     foreach ($ids as $id) {
    //         $product = $this->gamesController->getProductById($id);

    //         if ($product) {
    //             array_push($products, $product);
    //         }
    //     }

    //     return $products;
    // }

    // public function totalInCart($stripeID) {
    //     return in_array($stripeID, array_keys($_SESSION['cart'])) ? $_SESSION['cart'][$stripeID] : -1;
    // }

    // public function addToCart($stripeID, $quantity = 1) {
    //     if (!isset($_SESSION['cart'])) {
    //         $_SESSION['cart'] = [];
    //     } 

    //     if (!isset($_SESSION['cart'][$stripeID])) {
    //         $_SESSION['cart'][$stripeID] = $quantity;
    //     } else {
    //         $_SESSION['cart'][$stripeID] += $quantity;
    //     }
    // }

    // public function removeFromCart($stripeID, $quantity = 0) {
    //     $total = $this->totalInCart($stripeID);

    //     if ($total !== -1) {
    //         $total -= $quantity === 0 ? $total : $quantity;
    //         $_SESSION['cart'][$stripeID] = $total;

    //         if ($_SESSION['cart'][$stripeID] <= 0) {
    //             unset($_SESSION['cart'][$stripeID]);
    //         }
    //     }
    // }

    // public function getJsonEncoded($items) {
    //     return json_encode($items);
    // }
}