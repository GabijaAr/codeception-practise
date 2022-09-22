<?php

declare(strict_types=1);

namespace Tests\Support\Helper;
use Tests\Support\Page\Acceptance\ActivitiesPage;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class ActivitiesHelper extends \Codeception\Module
{
    public function getUserId() : int
    {
        $I = $this->getModule(name: 'REST');

        $userData = $I->sendGET(
            "https://control-develop-devdb.staging.cozone.com/v1/api/me"
        );
        $I->seeResponseCodeIsSuccessful();
        $user = json_decode($userData, true);
        return $user['company']['id']; 
    }

    public function generateYear(int $randomYear)
    {
        $I = $this->getModule(name: 'WebDriver');
        
        $userId= $this->getUserId();
        return $I->amOnPage("/ui/#/1/{$userId}/{$randomYear}");
    }

    public function navCalendarWArrow(int $year) : void
    {
        $I = $this->getModule(name: 'WebDriver');

        $I->waitForElementVisible(ActivitiesPage::DATAPICKER_CURRENT_YEAR, 60);
        $currentDate = $I->grabTextFrom(ActivitiesPage::DATAPICKER_CURRENT_YEAR);
        $dateSplitYear = str_split($currentDate, 4)[0];
        $currentYear = (int) $dateSplitYear;

        var_dump($currentYear);

        if($currentYear > $year)
        {
            while($currentYear > $year){
                $I->waitForElementClickable(ActivitiesPage::DATAPICKER_YEAR_PREVIOUS, 60);
                $I->click(ActivitiesPage::DATAPICKER_YEAR_PREVIOUS);
                $currentYear -= 1;
            }
        }elseif($currentYear < $year)
        {
            while($currentYear < $year){
                $I->waitForElementClickable(ActivitiesPage::DATAPICKER_YEAR_NEXT, 60);                
                $I->click(ActivitiesPage::DATAPICKER_YEAR_NEXT);
                $currentYear += 1; 
            }
        }
        $I->see("{$year}", '.year-picker__title');
    }
}
