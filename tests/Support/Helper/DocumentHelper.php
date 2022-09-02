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

    public function getDocumentAreaId($companyId)
    {
        $I = $this->getModule(name: 'REST');

        $I->haveHttpHeader('accept', 'application/json');        
        $I->haveHttpHeader('content-type', 'application/json');    
        $I->amBearerAuthenticated($passwordHelper->getToken()); 
        
        $I->sendGET(
            "https://documents-develop-devdb.staging.cozone.com/v1/api/companies/'{$companyId}'/document-areas"
        );
        $I->seeResponseCodeIsSuccessful();
    }

    public function createNewDirectory(
        $parentDirectoryId, 
        $directoryName, 
        $userId, 
        $permission, 
        $passwordHelper ) : void 
    {
        $I = $this->getModule(name: 'REST');

        $I->haveHttpHeader('accept', 'application/json');        
        $I->haveHttpHeader('content-type', 'application/json');    
        
        $I->sendPOST(
            'https://documents-develop-devdb.staging.cozone.com/v1/api/directories',[
                'parentDirectoryId' => "{$parentDirectoryId}",
                'name' => "{$directoryName}",
                'accessRules' => 
                    [
                    0 => 
                        [
                            'user' => 
                                [
                                'id' => "{$userId}",
                                ],
                            'permissions' => 
                                [
                                'browse' => true,
                                'delete' => true,
                                'files' => true,
                                'folders' => true,
                                ],
                            'permission' => "{$permission}",
                        ],
                ], 
            ]
        );
        $I->seeResponseCodeIsSuccessful();
    }

    #[Incomplete]
    public function uploadNewFile($passwordHelper) : void 
    {
        $I = $this->getModule(name: 'REST');

        $I->haveHttpHeader('Accept', 'application/json');        
        $I->haveHttpHeader('Content-type','multipart/form-data' );         
        $I->amBearerAuthenticated($passwordHelper->getToken());  

        // $path = codecept_data_dir();
        // $filename = 'd913d35c-915c-41db-a126-613d04694752.txt';
        $I->sendPOST(
            'https://documents-develop-devdb.staging.cozone.com/v1/api/files',[
                'contents' => null,
                'contentType' => "text/plain",
                'name' => 'FileForApi4',
                'relativePath' => 'API',
                'parentDirectoryId' => 2931119  ,              
            ],      
            ['contents' => [
                    'name' => 'd913d35c-915c-41db-a126-613d04694752.txt',
                    'type' => 'text/plain',
                    'size' => filesize(codecept_data_dir('d913d35c-915c-41db-a126-613d04694752.txt')),
                    'tmp_name' => codecept_data_dir('d913d35c-915c-41db-a126-613d04694752.txt'),

                ]]
            // ['contents' =>  $path . $filename]
        );
        $I->seeResponseCodeIsSuccessful();
    }
}
