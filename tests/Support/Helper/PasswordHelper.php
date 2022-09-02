<?php

declare(strict_types=1);

namespace Tests\Support\Helper;
use Codeception\Module;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class PasswordHelper extends Module
{
    private $defaultLoginScope = "app-portal app-documents"; 

    public function loginApi(string $username, string $password) : void
    {
        $I = $this->getModule(name: 'REST');

        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');

        $I->sendPost(
            '/api/v1/oauth2', [
                "grant_type" => "password",
                "client_id" => "publicclient",
                "username" => "{$username}",
                "password" => "{$password}",
                "scope" => "{$this->defaultLoginScope}"
                ]
        );
        $I->seeResponseCodeIsSuccessful();

        $this->token = $I->grabDataFromResponseByJsonPath('access_token')[0];
        $I->amBearerAuthenticated($this->token);  
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
    }

    public function changePasswordApi(string $password, string $newPassword) : void
    {
        $I = $this->getModule(name: 'REST');
    
        $I->sendPATCH(
            'https://portal-develop-devdb.staging.cozone.com/api/v1/me',[
                "newPassword" => "{$newPassword}",
                "newPasswordVerify" => "{$newPassword}",
                "currentPassword" => "{$password}"
            ]
        );
        $I->seeResponseCodeIsSuccessful();
    }

}
