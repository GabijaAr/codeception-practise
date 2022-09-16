<?php

declare(strict_types=1);

namespace Tests\Support\Page\Acceptance;

use Tests\Support\AcceptanceTester;
use Tests\Support\Helper\PasswordHelper;
// use Tests\Support\Page\Acceptance\PortalPage;


class LoginPage
{
    /**
     * @var \Tests\Support\AcceptanceTester;
     */

    public $mainUserAcc = [

        'username' => 'Acc5@testermail.com',
        'password' => 'PASS*w01rd'
    ];

    public $secondUserAcc = [
        'company' => '',
        'user' => 'Acc6 test company',
        'username' => 'Acc6@testermail.com',
        'password' => 'PASS*w01rd'
    ];


    public const URL = '/user/login';

    public const USERNAME_FIELD = ['css' => 'fds-text-field > input.form-control.form-control-lg']; 
    public const PASSWORD_FIELD = ['css' => 'fds-password-field input'];
    public const PASSWORD_FIELD_TEXT = ['css' => 'fds-password-field input[type="text"]'];

    public const LOGIN_BUTTON = ['css' => 'fds-button button[type=submit]'];
    public const LOGIN_FORM = ['css' => 'uil-login-user'];
    Public const CHECK_BOX = ['css' => 'fds-checkbox > label'];

    public const ALERT_ERROR = ['css' => '.alert-content'];
    public const ALERT_CONTENT = 'Error! The username/password combination is not correct. Try again! ';

    public const SEE_PASSWORD = ['css' => 'fds-icon-button'];

    protected $acceptanceTester;

    public function __construct(\Tests\Support\AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
    }

    public function login(array $user) : void
    {
        $I = $this->acceptanceTester;

        $I->waitAndFill(self::USERNAME_FIELD, $user['username']);
        $I->waitAndFill(self::PASSWORD_FIELD, $user['password']);
        $I->waitAndClick(self::LOGIN_BUTTON);
    }

    public function logout($portalPage) 
    {
        $I = $this->acceptanceTester;

        $I->waitForElementVisible(PortalPage::PORTAL_NAV_SECT, 60);
        $I->waitAndClick(PortalPage::SURVEY_HOTJAR);
        $I->waitAndClick(PortalPage::NAV_DROPDOWN);
        $I->waitForElementClickable(PortalPage::NAV_ITEM__MANAGE_LINKS, 60);
        $I->scrollTo(PortalPage::NAV_ITEM__MANAGE_LINKS);
        $I->waitAndClick(PortalPage::NAV_ITEM__LOGOUT);
        $I->waitForElementVisible(self::LOGIN_FORM, 60);
        $I->seeCurrentUrlEquals(self::URL);   
    }

}
