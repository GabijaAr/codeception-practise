<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;
use Tests\Support\Page\Acceptance\LoginPage;
use Tests\Support\Page\Acceptance\ActivitiesPage;
use Codeception\Util\Shared\Asserts;
use Tests\Support\Helper\ActivitiesHelper;

class ActivitiesCest
{

    private $secondUserAcc = [
        'company' => 'Test Company 2022',
        'user' => 'Acc5 test company',    
        'username' => 'Acc5@testermail.com',
        'password' => 'PASS*w01rd'
    ];

    private $mainUserAcc = [
        'company' => 'Test Company 2022',
        'user' => 'Acc6 test company',
        'username' => 'Acc6@testermail.com',
        'password' => 'PASS*w01rd'
    ];

    public function _before(AcceptanceTester $I, LoginPage $loginPage) : void
    {
        $I->setPageAndCookie(LoginPage::URL);  
        $I->loginApi($this->mainUserAcc);      
        $I->redirectToPage($this->mainUserAcc, ActivitiesPage::URL, $loginPage);
        $I->setCookie('automation_testing', 'selenium');
    }

    // tests
    public function tryToDragAndDropActivity(AcceptanceTester $I, ActivitiesPage $activitiesPage, LoginPage $loginPage) : void
    {


        $source = $activitiesPage->getSourceActivity('Approve preliminary reports');
        $target = $activitiesPage->getTargetCalendarSlot('2022-01-31T22:00:00.000Z');
        // $activitiesPage->dragAndDrop($source, $target);
      
        // $secondUser = $I->haveFriend('secondUser');
        // $secondUser->does(function (AcceptanceTester $I) use ($loginPage, $activitiesPage){
        //     $I->setPageAndCookieForFriend(ActivitiesPage::URL, $loginPage->secondUserAcc, $loginPage);
        //     $I->amOnUrl(ActivitiesPage::URL);
        //     $I->waitForElementNotVisible($activitiesPage->getSourceActivity('Approve preliminary reports'), 60);
        // });

        // $I->waitAndClick(['xpath' => "//button[text()[contains(., 'Send for approval' )]]"]);
        // $I->waitAndClick(['xpath' => "//form//button[text()[contains(., 'Select approver' )]]"]);
        // $I->waitAndFill(['xpath' => "//form//input[@placeholder='Type to search']"], $loginPage->secondUserAcc['user']);
        // $I->waitAndClick(['xpath' => "//form//input[1]"]);
        // $I->waitAndClick(['xpath' => "//form//button[text()[contains(., 'Send for approval' )]]"]); 

        // $secondUser->does(function (AcceptanceTester $I) use ($loginPage, $activitiesPage){
        //     $I->amOnUrl(ActivitiesPage::URL);
        //     $I->waitForElementVisible($activitiesPage->getSourceActivity('Approve preliminary reports'), 60);

        // });

        // $secondUser->leave();

    }

    public function tryToCalendarNavigation(AcceptanceTester $I, ActivitiesPage $activitiesPage, ActivitiesHelper $activitiesHelper) : void
    {
        $randomYear = rand(2010, 2022);
        $activitiesHelper->generateYear($randomYear);
        $activitiesHelper->navCalendarWArrow(2018);
    }

}
