<?php

// require_once "$path/controllers/google/drive_controller.php";

require_once "$path/vendor/autoload.php";

use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDrive;
use Google\Service\Drive\DriveFile as GoogleDriveFile;

use GuzzleHttp\Client as GuzzleClient;

class GoogleClientManager
{
    private static $instance = null;

    private const SERVICE_ACCOUNT_FILE = '/remote/Nexus_OAuth2_Client_ID.json';
    private const GAMES_FOLDER_ID = '1b6KuDPnX_fyN6t2LUHeiumPZclX32Ak0';
    private const UPLOAD_URL = 'https://www.googleapis.com/upload/drive/v3/files?uploadType=resumable';

    private $client_id;
    private $client_secret;

    private $refresh_token;
    private $access_token;

    private $client;
    private $drive_service;

    public $central_controller;
    public $drive_controller;

    public function __construct($central_controller)
    {
        global $path;

        $this->central_controller = $central_controller;

        $this->client_id = $_ENV['GOOGLE_CLIENT_ID'];
        $this->client_secret = $_ENV['GOOGLE_CLIENT_SECRET'];

        $this->client = new GoogleClient();
        $this->client->setAuthConfig($path . self::SERVICE_ACCOUNT_FILE);
        $this->client->addScope('https://www.googleapis.com/auth/drive');

        $this->client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);

        $this->drive_service = new GoogleDrive($this->client);

        $this->grantClientAccess();

        // $this->drive_controller = new DriveController($this);
    }

    public static function getInstance($central_controller)
    {
        if (self::$instance === null) {
            self::$instance = new GoogleClientManager($central_controller);
        }

        return self::$instance;
    }

    private function grantClientAccess()
    {
        $refresh_token = $this->client->getRefreshToken();

        if ($refresh_token) {
            $access_token_data = $this->client->fetchAccessTokenWithRefreshToken($refresh_token);

            if (isset($access_token_data['access_token'])) {
                $this->access_token = $access_token_data['access_token'];

                return true;
            }
        }

        return false;
    }

    public function getClient()
    {
        global $path;
        $client = new GoogleClient();
        $client->setAuthConfig($path . self::SERVICE_ACCOUNT_FILE);
        $client->addScope('https://www.googleapis.com/auth/drive');

        return $client;
    }

    // https://phppot.com/php/how-to-upload-files-to-google-drive-with-api-using-php/
    // https://developers.google.com/drive/api/guides/manage-uploads
    // https://docs.guzzlephp.org/en/stable/overview.html#installation
    public function createUploadSession($filepath)
    {
        // $client = $this->getClient();

        if ($this->client->isAccessTokenExpired()) {
            $this->grantClientAccess();
        }

        $fileMetadata = new GoogleDriveFile([
            'name' => 'testfile.jpg',
            'parents' => [self::GAMES_FOLDER_ID]
        ]);

        $httpClient = new GuzzleClient(['headers' => [
            'Authorization' => 'Bearer ' . $this->access_token,
            'Content-Type' => 'application/json',
            'X-Upload-Content-Type' => 'image/jpeg', // Set the correct MIME type
            'X-Upload-Content-Length' => filesize($filepath) // TODO: need filesize in param
        ]]);

        $response = $httpClient->post(self::UPLOAD_URL, ['body' => json_encode($fileMetadata->toSimpleObject())]);

        return $response->getHeader('Location')[0];
    }

    public function createUserFolder($user)
    {
        // $client = $this->getClient();
        // $drive_service = new GoogleDrive($client);

        $fileMetadata = new Google\Service\Drive\DriveFile([
            'name' => $user['id'],
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => [self::GAMES_FOLDER_ID]
        ]);

        try {
            $folder = $this->drive_service->files->create($fileMetadata, ['fields' => 'id']);

            return $folder->id;
        } catch (Exception $e) {
            echo 'An error occurred: ' . $e->getMessage();

            return null;
        }
    }

    // public function redirect()
    // {
    //     $client = $this->getClient();
    //     $auth_url = $client->createAuthUrl();

    //     header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
    // }
}
