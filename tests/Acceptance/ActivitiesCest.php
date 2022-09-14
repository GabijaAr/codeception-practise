<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;
use Tests\Support\Page\Acceptance\LoginPage;
use Tests\Support\Page\Acceptance\ActivitiesPage;



class ActivitiesCest
{
    public function _before(AcceptanceTester $I, LoginPage $loginPage)
    {
        $I->setPageAndCookie(LoginPage::URL);        
        $I->redirectToPage($loginPage->mainUserAcc, ActivitiesPage::URL, $loginPage);
        $I->setCookie('automation_testing', 'selenium');
    }

    // tests
    public function tryToDragAndDropActivity(AcceptanceTester $I, ActivitiesPage $activitiesPage) : void
    {
        $source = $activitiesPage->getSourceActivity('Approve preliminary reports');
        $target = $activitiesPage->getTargetCalendarSlot('2022-01-31T22:00:00.000Z');
        // $activitiesPage->dragAndDrop($source, $target);

        $I->waitAndClick(['xpath' => "//button[text()[contains(., 'Send for approval' )]]"], 60);
        
        $consultantUser = $I->haveFriend('consultantUser');
        $consultantUser->does(function (AcceptanceTester $I) use ($loginPage){
            $I->setPageAndCookieForFriend(DocumentsPage::URL, $loginPage->consultantUserAcc, $loginPage);

        });

    }

}
