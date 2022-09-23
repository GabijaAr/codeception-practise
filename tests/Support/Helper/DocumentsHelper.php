<?php

declare(strict_types=1);

namespace Tests\Support\Helper;

use Codeception\Module;
use Tests\Support\AcceptanceTester;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class DocumentsHelper extends \Codeception\Module
{

    public function getDocumentAreaId($companyId, $directoryName) : int
    {
        $I = $this->getModule(name: 'REST');

        $directoriesList= $I->sendGET(
            "https://documents-develop-devdb.staging.cozone.com/v1/api/companies/{$companyId}/document-areas"
        );
        $I->seeResponseCodeIsSuccessful();
        $directoriesList = json_decode($directoriesList, true);

        $directoryId = 0;
        foreach ($directoriesList as $key => $value) {
            if ($directoryName === $value['name']) {
                $directoryId += $value['id'];
            }         
        }
        return $directoryId;
    }

    public function getFileId($directoryId, $fileName) : int
    {
        $I = $this->getModule(name: 'REST');

        $I->sendGET(
            "https://documents-develop-devdb.staging.cozone.com/v1/api/directories/{$directoryId}"
        );
        $I->seeResponseCodeIsSuccessful();
        $documentArea = $I->grabDataFromResponseByJsonPath("__children")[0];

        $fileId = 0;
        foreach ($documentArea as $key => $value) {
            if ($fileName === $value['name']) {
                $fileId += $value['id'];
            }         
        }
        return $fileId;
    }

    public function createNewDirectory(array $directoryParam, $passwordHelper) : int 
    {
        $I = $this->getModule(name: 'REST');
               
        $I->sendPOST(
            'https://documents-develop-devdb.staging.cozone.com/v1/api/directories',[
                'parentDirectoryId' => $directoryParam['parentDirectoryId'],
                'name' => $directoryParam['directoryName'],
                'accessRules' => 
                    [
                        [
                            'user' => 
                                [
                                'id' => $directoryParam['userId'],
                                ],
                            'permissions' => 
                                [
                                'browse' => true,
                                'delete' => true,
                                'files' => true,
                                'folders' => true,
                                ],
                            'permission' => $directoryParam['permission'],
                        ],
                    ], 
            ]
        );
        $I->seeResponseCodeIsSuccessful();
        return $I->grabDataFromResponseByJsonPath('id')[0];        
    }

    private function createTmpFile(string $fileName, string $fileContents): string
    {
        $location = sys_get_temp_dir() . "/{$fileName}";

        if (file_exists($location)) {
            unlink($location);
        }
        file_put_contents($location, $fileContents);
        return $location;
    }

    public function uploadNewFile(array $fileParam, string $file) : int 
    {
        $I = $this->getModule(name: 'REST');

        $I->deleteHeader('Content-Type');
        $tmp = $this->createTmpFile($fileParam['name'], file_get_contents(codecept_data_dir($file)));
        $I->sendPOST(
            'https://documents-develop-devdb.staging.cozone.com/v1/api/files', 
            $fileParam, ['contents' => $tmp]
        );
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->seeResponseCodeIsSuccessful();
        return $I->grabDataFromResponseByJsonPath('id')[0];
    }
}
