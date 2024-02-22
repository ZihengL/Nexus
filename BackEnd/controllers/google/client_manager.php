<?php

require_once "$path/controllers/google/drive_controller.php";

require_once "$path/vendor/autoload.php";

use Google\Client;

class GoogleClientManager
{
    private static $instance = null;

    private const SERVICE_ACCOUNT_FILE = 'remote/Nexus_OAuth2_Client_ID.json';

    private $client;
    public $central_controller;
    public $drive_controller;

    private $client_id;
    private $client_secret;
    private $redirect_uri;

    private function __construct($central_controller)
    {
        global $path;

        $this->central_controller = $central_controller;

        // $this->client_id = $_ENV['GOOGLE_CLIENT_ID'];
        // $this->client_secret = $_ENV['GOOGLE_CLIENT_SECRET'];
        // $this->redirect_uri = $_ENV['GOOGLE_REDIRECT_URI'];

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
        $client = new Google\Client();
        $client->setClientId($this->client_id);
        $client->setClientSecret($this->client_secret);
        $client->setRedirectUri($this->redirect_uri);
        $client->setScopes(Google\Service\Drive::DRIVE);
        $client->setAccessType('offline');

        return $client;
    }

    public function redirect()
    {
        $client = $this->getClient();
        $auth_url = $client->createAuthUrl();

        header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
    }

    public function getAccess($user_id, $tokens)
    {
    }
}
