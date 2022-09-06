<?php

declare(strict_types=1);

namespace Tests\Support\Helper;
use Codeception\Module;
use Tests\Support\AcceptanceTester;
use Codeception\Attribute\Incomplete;


// here you can define custom actions
// all public methods declared in helper class will be available in $I

class DocumentHelper extends \Codeception\Module
{


    public function getDocumentAreaFiles($companyId)
    {
        $I = $this->getModule(name: 'REST');

        $I->haveHttpHeader('accept', 'application/json');        
        $I->haveHttpHeader('content-type', 'application/json');    
        $I->amBearerAuthenticated($passwordHelper->getToken()); 
        
        return $I->sendGET(
            "https://documents-develop-devdb.staging.cozone.com/v1/api/companies/'{$companyId}'/document-areas"
        );
        $I->seeResponseCodeIsSuccessful();
    }

    public function getFileId(){

    }

    public function createNewDirectory( array $directoryParam, $passwordHelper ) : int 
    {
        $I = $this->getModule(name: 'REST');

        $I->haveHttpHeader('accept', 'application/json');        
        $I->haveHttpHeader('content-type', 'application/json');    
        
        $I->sendPOST(
            'https://documents-develop-devdb.staging.cozone.com/v1/api/directories',[
                'parentDirectoryId' => $directoryParam['parentDirectoryId'],
                'name' => $directoryParam['directoryName'],
                'accessRules' => 
                    [
                    0 => 
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

    public function uploadNewFile(array $parametersFile, string $file) : int 
    {
        $I = $this->getModule(name: 'REST');

        $nullV = null;
        $I->deleteHeader('Content-Type');


        $tmp = $this->createTmpFile($parametersFile['name'], file_get_contents(codecept_data_dir($file)));

        $I->sendPOST('https://documents-develop-devdb.staging.cozone.com/v1/api/files', $parametersFile, ['contents' => $tmp]);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->seeResponseCodeIsSuccessful();
        return $I->grabDataFromResponseByJsonPath('id')[0];
    }

}
