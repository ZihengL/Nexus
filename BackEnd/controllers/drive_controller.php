<?php

require_once "$path/controllers/base_controller.php";
require_once "$path/vendor/autoload.php";

// https://github.com/googleapis/google-api-php-client
// composer require google/apiclient:^2.15.0
class DriveController extends BaseController
{
    private $client;

    public function __construct($central_controller, $pdo)
    {
        $this->client = new Google_Client();
        $this->client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $this->client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $this->client->setRedirectUri($_ENV['GOOGLE_CLIENT_URI']);
        $this->client->addScope(Google_Service_Drive::DRIVE);
        $this->client->setAccessType('offline');

        parent::__construct($central_controller);
    }
}
