<?php

declare(strict_types=1);

namespace Tests\Support\Helper;
use Tests\Support\AcceptanceTester;
// here you can define custom actions
// all public methods declared in helper class will be available in $I

class ActivitiesHelper extends \Codeception\Module
{
    public function getUserId()
    {
        $I = $this->getModule(name: 'REST');

        $userData = $I->sendGET(
            "https://control-develop-devdb.staging.cozone.com/v1/api/me"
        );
        $I->seeResponseCodeIsSuccessful();
        $user = json_decode($userData, true);
        return $userId = $user['company']['id']; 
    }

    public function generateYear($randomYear)
    {
        $I = $this->getModule(name: 'WebDriver');
        $userId= $this->getUserId();
        return $I->amOnPage("/ui/#/1/{$userId}/{$randomYear}");
    }

    public function navCalendarWArrow($year)
    {
        $I = $this->getModule(name: 'WebDriver');

        $currentDate = $I->grabTextFrom(['css' => '.year-picker__title']);
        $dateSplitYear = str_split($currentDate, 4)[0];
        $currentYear = (int) $dateSplitYear;

        $I->maximizeWindow();
        var_dump($currentYear);

        if($currentYear > $year)
        {
            var_dump('less');
            while($currentYear > $year) 
            {
                $I->waitForElementClickable(['xpath' => "//fds-layout-main//button/span[text()[contains(., 'Previous year' )]]"], 60);
                $I->click(['xpath' => "//fds-layout-main//button/span[text()[contains(., 'Previous year' )]]"]);
            }
        }
        elseif($currentYear < $year)
        {
            var_dump('more');
            while($currentYear < $year) 
            {
                $I->waitForElementClickable(['xpath' => "//fds-layout-main//button/span[text()[contains(., 'Next year' )]]"], 60);                
                $I->click(['xpath' => "//fds-layout-main//button/span[text()[contains(., 'Next year' )]]"]);
            }
        }
        $I->see('2018', '.year-picker__title');
        $I->waitForElementClickable(['xpath' => "//fds-layout-main//button[text()[contains(., 'Manage activities' )]]"], 60);        
        $I->click(['xpath' => "//fds-layout-main//button[text()[contains(., 'Manage activities' )]]"]);  
    }


    public function navCalendarDatePicker()
    {
        $I = $this->getModule(name: 'WebDriver');
    }

}
