<?php

declare(strict_types=1);

namespace Tests\Support\Page\Acceptance;


class ControlPage
{
    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public $usernameField = '#username';
     * public $formSubmitButton = "#mainForm input[type=submit]";
     */
    public const LINK_CONTROL = ['css' => 'a[href="https://idp-develop-devdb.staging.cozone.com/sso/control"]'];
    public const CONTROL_USER_LIST_SECT = ['css' => 'fds-card'];

    /**
     * @var \Tests\Support\AcceptanceTester;
     */
    protected $acceptanceTester;

    public function __construct(\Tests\Support\AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
        // you can inject other page objects here as well
    }

    public function redirectToControlApp($name, $password, $loginPage)
    {
        $I = $this->acceptanceTester;
        $loginPage->login($name, $password);
        $I->waitForElementClickable(PortalPage::PORTAL_NEWS_SECT, 60);
        $I->scrollTo(self::LINK_CONTROL);
        $I->executeJS('window.scrollTo(64,64);');
        $I->waitAndClick(self::LINK_CONTROL);
        $I->switchToNextTab();
        $I->waitForElementVisible(self::CONTROL_USER_LIST_SECT, 60);
    }

}
