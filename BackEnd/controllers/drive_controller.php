<?php

require_once "$path/vendor/autoload.php";

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

// https://github.com/googleapis/google-api-php-client
// composer require google/apiclient:^2.15.0
class DriveController
{
    private const SERVICE_ACCOUNT = 'nexus-414517-7a72c55fec4d.json';

    private $central_controller;
    private $client;
    private $service;

    public function __construct($central_controller, $pdo)
    {
        $this->central_controller = $central_controller;

        $this->client = new Google_Client();
        $this->client->setAuthConfig(self::SERVICE_ACCOUNT);
        $this->client->addScope('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);

        $this->service = new Drive($this->client);
    }

    
}
