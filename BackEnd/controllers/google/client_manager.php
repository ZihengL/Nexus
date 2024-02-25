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

    private const SERVICE_ACCOUNT_FILE = 'remote/Nexus_OAuth2_Client_ID.json';
    private const GAMES_FOLDER_ID = '1b6KuDPnX_fyN6t2LUHeiumPZclX32Ak0';
    private const UPLOAD_URL = 'https://www.googleapis.com/upload/drive/v3/files?uploadType=resumable';

    private $client_id;
    private $client_secret;

    private $client;
    private $drive_service;

    public $central_controller;
    public $drive_controller;

    private function __construct($central_controller)
    {
        global $path;

        $this->central_controller = $central_controller;

        $this->client_id = $_ENV['GOOGLE_CLIENT_ID'];
        $this->client_secret = $_ENV['GOOGLE_CLIENT_SECRET'];

        $this->client = new GoogleClient();
        $this->client->setAuthConfig($path . self::SERVICE_ACCOUNT_FILE);
        $this->client->addScope('https://www.googleapis.com/auth/drive');

        $this->drive_service = new GoogleDrive($this->client);

        // $this->client = new Google\Client();
        // $this->client->setApplicationName('Nexus');
        // $this->client->setScopes(Google\Service\Drive::DRIVE);
        // $this->client->setAuthConfig($path . self::SERVICE_ACCOUNT_FILE);
        // $this->client->setAccessType('offline');

        // $this->drive_controller = new DriveController($this);
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
        global $path;

        $client = new GoogleClient();
        $client->setAuthConfig($path . self::SERVICE_ACCOUNT_FILE);
        $client->addScope(GoogleDrive::DRIVE_FILE);
        // $client->setClientId($this->client_id);
        // $client->setClientSecret($this->client_secret);
        // $client->setRedirectUri($this->redirect_uri);
        // $client->setScopes(Google\Service\Drive::DRIVE);
        // $client->setAccessType('offline');

        return $client;
    }

    // https://phppot.com/php/how-to-upload-files-to-google-drive-with-api-using-php/
    // https://developers.google.com/drive/api/guides/manage-uploads
    // https://docs.guzzlephp.org/en/stable/overview.html#installation
    public function createUploadSession($filepath)
    {
        $fileMetadata = new GoogleDriveFile([
            'name' => 'testfile.jpg',
            'parents' => [self::GAMES_FOLDER_ID]
        ]);

        $httpClient = new GuzzleClient(['headers' => [
            'Authorization' => 'Bearer ' . $this->client->getAccessToken()['access_token'],
            'Content-Type' => 'application/json',
            'X-Upload-Content-Type' => 'image/jpeg', // Set the correct MIME type
            'X-Upload-Content-Length' => filesize($filepath) // TODO: need filesize in param
        ]]);

        $response = $httpClient->post(self::UPLOAD_URL, ['body' => json_encode($fileMetadata->toSimpleObject())]);

        return $response->getHeader('Location')[0];
    }

    public function createUserFolder($user)
    {
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

    // public function getAccess($user_id, $tokens)
    // {
    // }
}
