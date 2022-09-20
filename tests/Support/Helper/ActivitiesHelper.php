<?php

declare(strict_types=1);

namespace Tests\Support\Helper;

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

        var_dump($currentYear);

        if($currentYear > $year)
        {
            while($currentYear > $year){
                $I->waitForElementClickable(['xpath' => "//fds-year-picker/fds-icon-button[1]/button"], 60);
                $I->click(['xpath' => "//fds-year-picker/fds-icon-button[1]/button"]);
                $currentYear -= 1;
            }
        }elseif($currentYear < $year)
        {
            while($currentYear < $year){
                $I->waitForElementClickable(['xpath' => "//fds-year-picker/fds-icon-button[2]/button"], 60);                
                $I->click(['xpath' => "//fds-year-picker/fds-icon-button[2]/button"]);
                $currentYear += 1; 
            }
        }
        $I->see("{$year}", '.year-picker__title');
        $I->wait(5);
    }

}
