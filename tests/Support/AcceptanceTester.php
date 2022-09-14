<?php

declare(strict_types=1);

namespace Tests\Support;

use Tests\Support\Page\Acceptance\PortalPage;
/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
*/

class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;
    use \Codeception\Lib\Actor\Shared\Friend; 
    /**
     * Define custom actions here
     */
    public $cookieDefaultParams = [
        'path' => '/',
        'secure' => true,
        'httpOnly' => false,
        'domain' => 'idp-develop-devdb.staging.cozone.com'
    ];

    public function setPageAndCookie($path)
    {   
        $I = $this;
        $I->amOnPage($path);
        $I->maximizeWindow();
        $I->setCookie('OptanonAlertBoxClosed', '2022-08-23T11:29:30.562Z', $this->cookieDefaultParams);
        $I->reloadPage();
    } 

    public function setPageAndCookieForFriend($path, $user, $loginPage )
    {   
        $I = $this;
        $I->amOnUrl($path);
        $I->maximizeWindow();
        $I->resetCookie('OptanonAlertBoxClosed');
        $I->waitForElementVisible('uil-login-user', 60);    
        $I->reloadPage();              
        $I->setCookie('OptanonAlertBoxClosed', '2022-08-23T11:29:30.562Z', $this->cookieDefaultParams);
        $I->reloadPage();           
        $loginPage->login($user);

    }

        public function redirectToPage(array $user, string $url, $loginPage) : void
    {
        $I = $this;
        $loginPage->login($user);
        $I->waitForElementVisible(PortalPage::PORTAL_NEWS_SECT, 60);
        $I->amOnUrl($url);
    }

    public function waitAndSee($selector, $text)
    {
        $I = $this;
        $I->waitForElementVisible($selector, 60);
        $I->see($text, $selector);
    } 

    public function waitAndSeeElement($selector)
    {
        $I = $this;
        $I->waitForElementVisible($selector, 60);
        $I->seeElement( $selector);        
    }

    public function waitAndClick($selector)
    {
        $I = $this;
        $I->waitForElementClickable($selector, 60);
        $I->click($selector);
    }   

    public function waitAndFill($selector, $value)
    {
        $I = $this;
        $I->waitForElementClickable($selector, 60);
        $I->fillField($selector, $value);
    } 


    public function waitVisibleAndClick($selector)
    {
        $I = $this;
        $I->waitForElementVisible($selector, 60);
        $I->click($selector);
    }
}

