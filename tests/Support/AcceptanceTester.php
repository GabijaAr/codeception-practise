<?php

declare(strict_types=1);

namespace Tests\Support;

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
    /**
     * Define custom actions here
     */

    public function setPageAndCookie($path)
    {
        $I = $this;
        $I->amOnPage($path);
        $I->maximizeWindow();
        $I->setCookie('OptanonAlertBoxClosed', '2022-08-23T11:29:30.562Z');
        $I->reloadPage();
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


    public function checkCheckbox($selector)
    {
        $I = $this;
        $I->waitForElementVisible($selector, 60);
        $I->click($selector);
    }
}

