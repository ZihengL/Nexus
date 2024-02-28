<?php

require_once "$path/controllers/database_manager.php";
require_once "$path/controllers/games_controller.php";
require_once "$path/controllers/users_controller.php";
require_once "$path/controllers/tokens_controller.php";
require_once "$path/controllers/reviews_controller.php";
require_once "$path/controllers/gameTags_controller.php";
require_once "$path/controllers/tags_controller.php";
// require_once "$path/controllers/google/client_manager.php";

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
    public $reviews_controller;
    public $gamestags_contoller;
    public $tags_controller;

    public $google_client_manager;

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
        $this->reviews_controller = new ReviewsController($this, $pdo);
        $this->gamestags_contoller = new GameTagsController($this, $pdo);
        $this->tags_controller = new TagsController($this, $pdo);

        // $this->google_client_manager = GoogleClientManager::getInstance($this);
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new CentralController();
        }

        return self::$instance;
    }

    // GETTERS

    private function getControllersAsArray()
    {
        return [
            $this->tokens_controller,
            $this->users_controller,
            $this->games_controller,
            $this->reviews_controller,
            $this->gamestags_contoller,
            $this->tags_controller
        ];
    }

    public function restrictAccessOnJoinedTables($joined_tables = [])
    {
        if (!is_array($joined_tables) || count($joined_tables) === 0) return;

        foreach ($joined_tables as $tablename => $included_columns) {
            foreach ($this->getControllersAsArray() as $controller) {
                $controller_table = $controller->getTableName();

                if ($tablename === $controller_table) {
                    $included_columns = $controller->restrictAccess($included_columns);
                }
            }
        }

        return $joined_tables;
    }

    public function getAllMatching($controller, $filters = [], $sorting = [], $included_columns = [])
    {
        return $controller->getAllMatching($filters, $sorting, $included_columns);
    }

    // COMMANDS

    public function setRoutines($toRunning)
    {
        $this->routines_controller->setRunningState($toRunning);
    }
}
