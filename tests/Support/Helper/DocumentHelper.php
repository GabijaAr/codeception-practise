<?php

declare(strict_types=1);

namespace Tests\Support\Helper;

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
        // 

    }

    public function createNewDirectory($parentDirectoryId, $directoryName, $userId, $permission, $passwordHelper ) : void 
    {
        $I = $this->getModule(name: 'REST');

        $I->haveHttpHeader('accept', 'application/json');        
        $I->haveHttpHeader('content-type', 'application/json');    
        $I->amBearerAuthenticated($passwordHelper->getToken());  

        $I->sendPOST(
            'https://documents-develop-devdb.staging.cozone.com/v1/api/directories',[
                'parentDirectoryId' => "{$parentDirectoryId}",
                'name' => "{$directoryName}",
                'accessRules' => 
                    array (
                    0 => 
                    array (
                            'user' => 
                                array (
                                'id' => "{$userId}",
                                ),
                            'permissions' => 
                                array (
                                'browse' => true,
                                'delete' => true,
                                'files' => true,
                                'folders' => true,
                                ),
                            'permission' => "{$permission}",
                    ),
                    ), 
            ]
        );
        $I->seeResponseCodeIsSuccessful();
    }

    public function uploadNewFile($passwordHelper) : void 
    {
        $I = $this->getModule(name: 'REST');

        $I->haveHttpHeader('accept', 'application/json');        
        $I->haveHttpHeader('content-type', 'application/json');    
        $I->amBearerAuthenticated($passwordHelper->getToken());  

        $I->sendPOST(
            'https://documents-develop-devdb.staging.cozone.com/v1/api/files',[
                'contentType' => 'txt',
                'contents' => codecept_data_dir('d913d35c-915c-41db-a126-613d04694752.txt'),
                'name' => 'FileForApi2',
                'relativePath' => 'test api1',
                'parentDirectoryId' => 2930954
            ]
        );
        $I->seeResponseCodeIsSuccessful();
    }

}
