<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;
use Tests\Support\Page\Acceptance\LoginPage;
use Tests\Support\Page\Acceptance\ActivitiesPage;
use Codeception\Util\Shared\Asserts;


class ActivitiesCest
{
    public function _before(AcceptanceTester $I, LoginPage $loginPage)
    {
        $I->setPageAndCookie(LoginPage::URL);        
        $I->redirectToPage($loginPage->mainUserAcc, ActivitiesPage::URL, $loginPage);
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

}
