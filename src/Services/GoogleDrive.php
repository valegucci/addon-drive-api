<?php

namespace Vale\Addons\Drive\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class GoogleDrive
{

    protected $client;

    protected $service;

    public function __construct()
    {
        $this->client = new Client();
        $key = base_path() . "/" . Config::get('addons.addon-drive.google-service-account-key');

        $this->client->setAuthConfig($key);

        $this->client->addScope(Drive::DRIVE);

        $this->client->setApplicationName("Copier");
        $this->service = new Drive($this->client);

        /* If we have an access token */
        if (Cache::has('service_token')) {
            $this->client->setAccessToken(Cache::get('service_token'));
        }

        Cache::forever('service_token', $this->client->getAccessToken());
    }

    public function moveFileToFolder($fileId, $folderId)
    {
        $emptyFileMetadata = new DriveFile();
        // Retrieve the existing parents to remove
        $file = $this->service->files->get($fileId, array('fields' => "id, parents, name"));
        if (empty($file->parents)) {
            throw new \Exception("Parents are empty");
        }
        $previousParents = join(',', $file->parents);
        // Move the file to the new folder
        $file = $this->service->files->update($fileId, $emptyFileMetadata, array(
            'addParents' => $folderId,
            'removeParents' => $previousParents,
            'fields' => 'id, parents'));
            
        return $file->parents;
    }
}
