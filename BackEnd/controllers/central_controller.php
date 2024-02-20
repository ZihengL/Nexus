<?php

require_once "$path/controllers/database_manager.php";

require_once "$path/controllers/games_controller.php";
require_once "$path/controllers/users_controller.php";
require_once "$path/controllers/tokens_controller.php";

require_once "$path/controllers/google/drive_controller.php";

require_once "$path/remote/routines.php";

use Dotenv\Dotenv as Dotenv;


class CentralController
{
    private static $instance = null;

    public $database_manager;
    public $tokens_controller;
    public $routines_controller;
    public $users_controller;
    public $games_controller;

    public $drive_controller;

    // CONSTRUCTOR

    private function __construct()
    {
        global $path;
        $dotenv = Dotenv::createImmutable($path);
        $dotenv->load();

        $this->database_manager = DatabaseManager::getInstance();
        $pdo = $this->database_manager->getPDO();

        $this->tokens_controller = new TokensController($this, $pdo);

        $this->users_controller = new UsersController($this, $pdo);
        $this->games_controller = new GamesController($this, $pdo);

        $this->drive_controller = new DriveController($this);
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new CentralController();
        }

        return self::$instance;
    }

    // GETTERS

    public function getAllMatching($controller, $filters = [], $sorting = [], $included_columns = [])
    {
        return $controller->getAllMatching($filters, $sorting, $included_columns);
    }

    // COMMANDS

    public function setRoutines($toRunning)
    {
        $this->routines_controller->setRunningState($toRunning);
    }

    // USER

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
