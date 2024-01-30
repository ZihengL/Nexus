<?php

require_once "$path/controllers/database.php";
// require_once "$path/controllers/reviews.php";
// require_once "$path/controllers/notifications.php";
require_once "$path/controllers/gamescontroller.php";
require_once "$path/controllers/userscontroller.php";

require_once "$path/remote/secrets.php";

class CentralController {
    private static $instance = null;

    private $databaseManager;
    public $usersController;
    public $notificationsController;
    public $reviewsController;
    public $gamesController;

    private function __construct($dbUsername, $dbPassword) {
        global $path;
        
        $this->databaseManager = DBManager::getInstance($dbUsername, $dbPassword);
        $pdo = $this->databaseManager->getPDO();
        $this->usersController = new UsersController($pdo);
        // $this->newslettersController = new NotificationsController($pdo);
        $this->gamesController = new GamesController($pdo);
    }

    public static function getInstance($dbUsername, $dbPassword) {
        if (self::$instance == null) {
            self::$instance = new centralController($dbUsername, $dbPassword);
        }

        return self::$instance;
    }

    //

    // public function getUsersController() {
    //     return $this->usersController;
    // }

    // public function getNewslettersController() {
    //     return $this->newslettersController;
    // }

    // public function getGamesController() {
    //     return $this->gamesController;
    // }

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