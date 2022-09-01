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

    public const PORTAL_NAV_SECT = ['css' => 'fds-header-navbar'];    
    public const PORTAL_NEWS_SECT = ['css'=> 'prt-portal prt-news'];
    public const SURVEY_HOTJAR = ['css' => 'button._hj-kWRoL__styles__openStateToggle'];
    
    public const NAV_DROPDOWN = ['css' => 'fds-header-user > button.user.nav-link'];
    public const NAV_ITEM__MANAGE_LINKS = ['css' => 'fds-header-user-nav  fds-header-user-nav-item  a[href="/admin/#/links"]'];
    public const NAV_ITEM__LOGOUT = ['xpath' => '/html/body/prt-app/div/prt-header/fds-header/fds-header-navbar/div/fds-header-nav/fds-header-nav-item/fds-header-user/div/div/fds-header-user-nav/fds-header-user-nav-item[14]'];    

    public const NAW_PERSONAL_INFO = ['css' => 'a[href="https://payroll-develop-devdb.staging.cozone.com/ui/profile"]'];
    public const NAV_DROPDOWN_PERSONAL_INFO = ['css' => '.nav-item.visma-user-settings'];
    public const NAV_LOGOUT = ['css' => 'a[data-gtm-id="logout"]'];
    
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
