<?php

declare(strict_types=1);

namespace Tests\Support\Page\Acceptance;

class PortalPage
{
    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public $usernameField = '#username';
     * public $formSubmitButton = "#mainForm input[type=submit]";
     */

    public const URL_PORTAL = '/ui/#/';
    
    public const NAV_DROPDOWN = ['css' => 'fds-header-nav-item'];
    public const NAW_PERSONAL_INFO = ['css' => 'a[href="https://payroll-develop-devdb.staging.cozone.com/ui/profile"]'];
    public const NAV_DROPDOWN_PERSONAL_INFO = ['css' => '.nav-item.visma-user-settings'];
    public const NAV_LOGOUT = ['css' => 'a[data-gtm-id="logout"]'];
    public const PORTAL_NEWS_SECT = ['css'=> 'prt-portal prt-news'];
    
    /**
     * @var \Tests\Support\AcceptanceTester;
     */

    protected $acceptanceTester;

    public function __construct(\Tests\Support\AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
        // you can inject other page objects here as well
    }


}
