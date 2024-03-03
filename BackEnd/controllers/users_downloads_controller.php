<?php
require_once "$path/controllers/base_controller.php";
require_once "$path/models/users_downloads_model.php";

class UsersDownloadsController extends BaseController
{
    protected $userID = 'userID';
    protected $gameID = 'gameID';
    protected $downloadDate = 'downloadDate';

    public function __construct($central_controller, $pdo)
    {
        $this->model = new UsersDownloadsModel($pdo);
        parent::__construct($central_controller);
    }
}
