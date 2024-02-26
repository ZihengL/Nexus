<?php

require_once 'vendor/autoload.php';

class GoogleDriveManager
{
    public $central_controller;
    private $client;
    private $service;

    public function __construct($central_controller)
    {
        $this->central_controller = $central_controller;

        global $path;
        $this->client = new Google\Client();
        $this->client->setAuthConfig($path . $_ENV['GOOGLE_OAUTH2_CREDS']);
        $this->client->addScope(Google\Service\Drive::DRIVE);

        $this->service = new Google\Service\Drive($this->client);
    }

    public function uploadFile($filePath, $mimeType)
    {
        $file = new Google\Service\Drive\DriveFile();
        $file->setName(basename($filePath));

        $result = $this->service->files->create(
            $file,
            array(
                'data' => file_get_contents($filePath),
                'mimeType' => $mimeType,
                'uploadType' => 'multipart'
            )
        );

        return $result;
    }

    public function createUploadSession($fileName, $mimeType, $folderId = null)
    {
        $file = new Google\Service\Drive\DriveFile([
            'name' => $fileName,
            'parents' => $folderId ? [$folderId] : null
        ]);

        $params = [
            'uploadType' => 'resumable',
            'fields' => 'id'
        ];

        try {
            $response = $this->service->files->create($file, $params);
            $uploadUrl = $response->getResumeUri(); // Retrieve the session URI for direct upload
            return $uploadUrl;
        } catch (Exception $e) {
            // Handle error (e.g., log this error, return a message, etc.)
            return null; // or handle as appropriate
        }
    }
}
