<?php
require_once "$path/controllers/database_manager.php";
// require_once "$path/controllers/google/client_manager.php";

require_once "$path/controllers/base_controller.php";
require_once "$path/controllers/tokens_controller.php";
require_once "$path/controllers/users_controller.php";
require_once "$path/controllers/games_controller.php";
require_once "$path/controllers/reviews_controller.php";
require_once "$path/controllers/tags_controller.php";
require_once "$path/controllers/transactions_controller.php";

require_once "$path/controllers/multiplicity/gamestags_controller.php";
require_once "$path/controllers/multiplicity/users_downloads_controller.php";

require_once "$path/remote/routines.php";

use Dotenv\Dotenv as Dotenv;

class CentralController
{
    private static $instance = null;

    // MANAGERS
    public $database_manager;
    // public  $google_client_manager;

    // TABLES
    // public $routines_controller;
    public $tokens_controller;
    public $users_controller;
    public $games_controller;
    public $reviews_controller;
    public  $tags_controller;
    public $transactions_controller;

    // RELATIONAL TABLES
    public  $gamestags_controller;
    public  $users_downloads_controller;

    private function __construct()
    {
        global $path;

        $dotenv = Dotenv::createImmutable($path);
        $dotenv->load();

        $this->database_manager = DatabaseManager::getInstance();
        // $this->google_client_manager = GoogleClientManager::getInstance($this);

        $pdo = $this->database_manager->getPDO();
        $this->tokens_controller = new TokensController($this, $pdo);
        $this->users_controller = new UsersController($this, $pdo);
        $this->games_controller = new GamesController($this, $pdo);
        $this->reviews_controller = new ReviewsController($this, $pdo);
        $this->tags_controller = new TagsController($this, $pdo);
        $this->transactions_controller = new TransactionsController($this, $pdo);

        // Relational tables
        $this->gamestags_controller = new GamestagsController($this, $pdo);
        $this->users_downloads_controller = new UsersDownloadsController($this, $pdo);
    }

    public static function getInstance()
    {
        if (self::$instance == null)
            self::$instance = new CentralController();

        return self::$instance;
    }

    /*******************************************************************/
    /****************************** GETTERS ****************************/
    /*******************************************************************/

    public function parseRequest($table, $action, $data = null)
    {
        if ($controller = BaseController::getController($table))
            if ($controller->isValidAction($action)) {
                try {
                    return $this->parseData($controller, $action, $data);
                } catch (Exception $e) {
                    throw new Exception("Failed to parse request data: {$e->getMessage()}");
                }
            }
    }

    private function parseData($controller, $action, $data)
    {
        if ($data) {
            if ($controller->isPrivilegedAction($action))
                $data = $controller->verifyCredentials($data);

            return $controller->$action($data);
        }

        return $controller->$action();
    }

    // COMMANDS

    // private function setRoutines($toRunning)
    // {
    //     $this->routines_controller->setRunningState($toRunning);
    // }
}
