<?php

require_once "$path/vendor/autoload.php";

use Google\Client;
use Google\Collection;
use Google\Service;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

// https://github.com/googleapis/google-api-php-client
// composer require google/apiclient:^2.15.0
class DriveController
{
    private const SERVICE_ACCOUNT_FILE = 'remote/nexus-414517-7a72c55fec4d.json';
    private const GAMES_FOLDER_ID = '1b6KuDPnX_fyN6t2LUHeiumPZclX32Ak0';

    private $central_controller;
    private $client;
    // private $permission;
    private $service;

    public function __construct($central_controller)
    {
        global $path;

        $this->central_controller = $central_controller;

        $this->client = new Google\Client();
        $this->client->setApplicationName('Nexus');
        $this->client->setScopes(Google\Service\Drive::DRIVE);
        $this->client->setAuthConfig($path . self::SERVICE_ACCOUNT_FILE);
        $this->client->setAccessType('offline');

        $this->service = new Google\Service\Drive($this->client);
    }

    private function createFolder($folder_name, $parent_folder_id)
    {
        $fileMetadata = new Google\Service\Drive\DriveFile([
            'name' => $folder_name,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => [$parent_folder_id]
        ]);

        try {
            $folder = $this->service->files->create($fileMetadata, ['fields' => 'id']);
            return $folder->id;
        } catch (Exception $e) {
            echo 'An error occurred: ' . $e->getMessage();
            return null;
        }
    }

    public function createUserSubfolder($user_id)
    {
        return $this->createFolder($user_id, self::GAMES_FOLDER_ID);
    }
}
