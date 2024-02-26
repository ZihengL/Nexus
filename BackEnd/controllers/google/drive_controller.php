<?php

require_once "$path/vendor/autoload.php";

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

// https://github.com/googleapis/google-api-php-client
// composer require google/apiclient:^2.15.0
class DriveController
{
    private const SCOPE = 'https://www.googleapis.com/auth/drive';
    private const GAMES_FOLDER_ID = '1b6KuDPnX_fyN6t2LUHeiumPZclX32Ak0';

    private $client_manager;
    private $service;

    public function __construct($client_manager)
    {
        $this->client_manager = $client_manager;

        $this->service = new Google\Service\Drive($this->client_manager->getClient());
        $this->client_manager->getClient()->setScopes([self::SCOPE]);
    }

    // GENERAL

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

    private function updateFolderName($new_folder_name, $folder_id)
    {
        $fileMetadata = new Google\Service\Drive\DriveFile([
            'name' => $new_folder_name
        ]);

        try {
            $this->service->files->update($folder_id, $fileMetadata, ['fields' => 'id, name']);

            return true;
        } catch (Exception $e) {
            echo 'An error occurred: ' . $e->getMessage();
            return false;
        }
    }

    // SPECIFIC REQUESTS

    public function createUserSubfolder($user_id)
    {
        return $this->createFolder($user_id, self::GAMES_FOLDER_ID);
    }
}
