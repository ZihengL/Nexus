<?php

require_once "$path/vendor/autoload.php";
require_once "$path/controllers/google/drive_controller.php";

use Google\Client;

class GoogleClientManager
{
    private static $instance = null;

    private const SERVICE_ACCOUNT_FILE = 'remote/nexus-414517-7a72c55fec4d.json';

    private $client;
    public $central_controller;
    public $drive_controller;

    private function __construct($central_controller)
    {
        global $path;

        $this->central_controller = $central_controller;

        $this->client = new Google\Client();
        $this->client->setApplicationName('Nexus');
        $this->client->setScopes(Google\Service\Drive::DRIVE);
        $this->client->setAuthConfig($path . self::SERVICE_ACCOUNT_FILE);
        $this->client->setAccessType('offline');

        $this->drive_controller = new DriveController($this);
    }

    public static function getInstance($central_controller)
    {
        if (self::$instance === null) {
            self::$instance = new GoogleClientManager($central_controller);
        }

        return self::$instance;
    }

    public function getClient()
    {
        return $this->client;
    }
}
