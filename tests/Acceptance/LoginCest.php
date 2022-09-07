<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;
use Facebook\WebDriver\WebDriverKeys;
use Codeception\Util\Shared\Asserts;
use Tests\Support\Page\Acceptance\LoginPage;
use Tests\Support\Page\Acceptance\PortalPage;
use Tests\Support\Helper\PasswordHelper;

class LoginCest
{

    private $username = 'testAcc1Sample@terstermail.com';
    private $password = 'STRONG-test1API';
    private $newPassword = 'TEST1-strong#';
    
    public function _before(
        AcceptanceTester $I,  
        PasswordHelper $passwordHelper, 
        \Codeception\Scenario $scenario) : void
    {
        $I->setPageAndCookie(LoginPage::URL);
    
        if($scenario->current('name') === 'tryToNewPassword')
        {
            $I->loginApi($this->username, $this->password);
        }

    }

    public function _after( AcceptanceTester $I, \Codeception\Scenario $scenario) : void
    {
        if($scenario->current('name') === 'tryToNewPassword')
        {
            $I->changePasswordApi($this->newPassword, $this->password); 
        }

    }

    // tests
    public function tryToLoginWithExistingCredentials(AcceptanceTester $I, LoginPage $loginPage) : void
    {
        $loginPage->login($this->username, $this->password);
        $I->waitForElementVisible(PortalPage::PORTAL_NEWS_SECT, 60);
        $I->seeCurrentUrlEquals(PortalPage::URL_PORTAL);

    } 
    public function tryToLoginWithNonExistingCredentials(AcceptanceTester $I, LoginPage $loginPage) : void
    {
        $loginPage->login('testAcc1@terstermail.com', $this->password);
        $I->waitAndSee(LoginPage::ALERT_ERROR, LoginPage::ALERT_CONTENT);
        $I->seeCurrentUrlEquals(LoginPage::URL);
    } 

    public function tryToRememberMe(AcceptanceTester $I, LoginPage $loginPage, PortalPage $portalPage) : void
    {
        $I->waitVisibleAndClick(LoginPage::CHECK_BOX);
        $loginPage->login($this->username, $this->password);
        $loginPage->logout($portalPage);
        $I->waitAndSeeElement(LoginPage::USERNAME_FIELD);
        $I->seeInField(LoginPage::USERNAME_FIELD, $this->username);
    }

    public function tryToCheckPasswordVisibility(AcceptanceTester $I) : void
    { 
        $I->waitAndFill(LoginPage::PASSWORD_FIELD, $this->password);
        $I->dontSeeElement(LoginPage::PASSWORD_FIELD_TEXT);
        $I->click(LoginPage::SEE_PASSWORD);
        $I->seeElement(LoginPage::PASSWORD_FIELD_TEXT);     
    }

    public function tryToLogout(
        AcceptanceTester $I, 
        LoginPage $loginPage, 
        PortalPage $portalPage, 
        PasswordHelper $passwordHelper) : void
    {
        $loginPage->login($this->username, $this->password);
        $loginPage->logout($portalPage);
    }
    
    public function tryToCheckEnterKey(AcceptanceTester $I) : void
    {
        $I->waitAndFill(LoginPage::USERNAME_FIELD, $this->username);
        $I->waitAndFill(LoginPage::PASSWORD_FIELD, $this->password);
        $I->pressKey(LoginPage::PASSWORD_FIELD, WebDriverKeys::ENTER);
        $I->waitForElementVisible(PortalPage::PORTAL_NEWS_SECT, 60);
        $I->seeCurrentUrlEquals(PortalPage::URL_PORTAL);
    }

    public function tryToNewPassword (AcceptanceTester $I, LoginPage $loginPage) : void
    {
            $I->changePasswordApi($this->password, $this->newPassword);

            $loginPage->login($this->username, $this->newPassword);
            $I->waitForElementVisible(PortalPage::PORTAL_NEWS_SECT, 60);
            $I->seeCurrentUrlEquals(PortalPage::URL_PORTAL);           
    }

}
