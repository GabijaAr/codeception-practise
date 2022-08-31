<?php


namespace Tests\Api;

use Tests\Support\ApiTester;

class PasswordCest
{

    public $username = 'testAcc1Sample@terstermail.com';
    public $password = 'STRONG-test1API';
    public $newPassword = 'TEST1-strong#';

    public function _before(ApiTester $I)
    {
    }

    // tests

    // public function tryToApiLogin(ApiTester $I)
    // {
    //     $I->haveHttpHeader('accept', 'application/json');
    //     $I->haveHttpHeader('content-type', 'application/json');

    //     $I->sendPost(
    //         'https://idp-develop-devdb.staging.cozone.com/api/v1/oauth2', [
    //             "grant_type" => "password",
    //             "client_id" => "publicclient",
    //             "username" => "{$this->username}",
    //             "password" => "{$this->password}",
    //             "scope" => "app-portal app-documents"
    //             ]
    //     );

    //     $token = $I->grabDataFromResponseByJsonPath('access_token')[0];

    //     $I->haveHttpHeader('accept', 'application/json');        
    //     $I->haveHttpHeader('content-type', 'application/json');
    //     $I->haveHttpHeader('Authorization', "Bearer {$token}");        

    //     $I->sendPATCH(
    //         'https://portal-develop-devdb.staging.cozone.com/api/v1/me',[
    //             "newPassword" => "{$this->newPassword}",
    //             "newPasswordVerify" => "{$this->newPassword}",
    //             "currentPassword" => "{$this->password}"
    //         ]
    //     );

    //     $I->seeResponseCodeIsSuccessful();

    // }
}
