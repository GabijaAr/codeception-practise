<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;
use Tests\Support\Page\Acceptance\LoginPage;
use Tests\Support\Page\Acceptance\ActivitiesPage;
use Codeception\Util\Shared\Asserts;
use Tests\Support\Helper\ActivitiesHelper;

class ActivitiesCest
{
    private $mainUserAcc = [
        'company' => 'Test Company 2022',
        'user' => 'Acc6 test company',
        'username' => 'Acc6@testermail.com',
        'password' => 'PASS*w01rd'
    ];

    private $activityParam = [
        'year' => 2018,
        'selectedDate' => 'Wed 20, June',
        'activity' => 'Operational meeting',       
    ];

    private function getRandomYear() : int {return rand(2010, 2022);} 



    public function _before(AcceptanceTester $I, LoginPage $loginPage) : void
    {
        $I->setPageAndCookie(LoginPage::URL);  
        $I->loginApi($this->mainUserAcc);      
        $I->redirectToPage($this->mainUserAcc, ActivitiesPage::URL, $loginPage);
        $I->setCookie('automation_testing', 'selenium');
    }
    
    public function _after(AcceptanceTester $I, ActivitiesPage $activitiesPage, \Codeception\Scenario $scenario) : void
    {
        if($scenario->current('name') === 'tryToCalendarNavigation')
        {
            $activitiesPage->deleteActivity($this->activityParam);
        }
    }


    // tests
    public function tryToCalendarNavigation(AcceptanceTester $I, ActivitiesPage $activitiesPage, ActivitiesHelper $activitiesHelper) : void
    {
        $activitiesHelper->generateYear($this->getRandomYear());
        $activitiesHelper->navCalendarWArrow($this->activityParam['year']);
        $activitiesPage->createActivity($this->activityParam, $this->mainUserAcc['company']);       
        $activitiesPage->navCalendarWDatepicker($this->activityParam['activity']);        
    }

}
