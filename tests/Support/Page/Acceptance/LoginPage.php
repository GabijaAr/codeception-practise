<?php

declare(strict_types=1);

namespace Tests\Support\Page\Acceptance;

use Tests\Support\AcceptanceTester;
// use Tests\Support\Page\Acceptance\PortalPage;


class LoginPage
{
    /**
     * @var \Tests\Support\AcceptanceTester;
     */


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

    public function login($name, $password)
    {
        $I = $this->acceptanceTester;

        $I->waitAndFill(self::USERNAME_FIELD, $name);
        $I->waitAndFill(self::PASSWORD_FIELD, $password);
        $I->click(self::LOGIN_BUTTON);
    }

    public function logout($portalPage)
    {
        $I = $this->acceptanceTester;

        $I->waitAndClick(PortalPage::NAV_DROPDOWN);
        // $I->click(PortalPage::NAW_PERSONAL_INFO);
        // $I->switchToNextTab();
        // $I->waitAndClick(PortalPage::NAV_DROPDOWN_PERSONAL_INFO);
        $I->click(PortalPage::NAV_LOGOUT);
        $I->waitForElementVisible(self::LOGIN_FORM, 60);
        $I->seeCurrentUrlEquals(self::URL);   
    }

}
