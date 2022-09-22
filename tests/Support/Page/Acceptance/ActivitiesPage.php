<?php

declare(strict_types=1);

namespace Tests\Support\Page\Acceptance;
use Tests\Support\AcceptanceTester;

class ActivitiesPage
{
    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public $usernameField = '#username';
     * public $formSubmitButton = "#mainForm input[type=submit]";
     */

    /**
     * @var \Tests\Support\AcceptanceTester;
     */

    public const URL = 'https://activities-develop-devdb.staging.cozone.com';
    public const ADD_ACTIVITY_FIELD_TYPE_BTN = ['xpath' => "//fds-selector-field[@formcontrolname='type']//button"];
    public const ADD_ACTIVITY_FIELD_OWNER_BTN = ['css' => "fds-selector-field[formcontrolname='owner'] button"];
    public const ADD_ACTIVITY_FIELD_ADD_BTN = ['xpath' => "//act-activity-modal//button[text()[contains(., 'Save' )]]"];    
    public const ADD_BTN = ['xpath' => "//fds-layout-main//button[text()[contains(., 'Save' )]]"];      
    public const EDIT_ACTIVITY_DELETE = ['xpath' => "//act-activity-modal//button[text()[contains(., 'Delete activity' )]]"];      
    public const DATAPICKER_MONTH = ['css' => "div.month-picker__title > fds-icon-button > button"];      
    public const DATAPICKER_SELECT_MONTH = ['css' => "button.date-picker__month[aria-label='Fri Jun 01 2018']"];      
    public const CANCEL_BUTTON = ['css' => 'fds-icon-button[icon="cancel"] > button'];      
    public const DATAPICKER_YEAR_PREVIOUS = ['xpath' => "//fds-year-picker/fds-icon-button[1]/button"];      
    public const DATAPICKER_YEAR_NEXT = ['xpath' => "//fds-year-picker/fds-icon-button[2]/button"];
    public const DATAPICKER_CURRENT_YEAR = ['css' => '.year-picker__title'];      
    
    public function getAddActivityTitle($title) : array {return ['css' => "[title='{$title}']"];} 
    public function getSelectActivityType($activity) : array {return ['xpath' => "//fds-selector-menu//button[text()[contains(., '{$activity}' )]]"];} 
    public function getSelectActivityCompany($company) : array {return ['xpath' => "//fds-selector-menu-item/button[text()[contains(., '{$company}' )]]"];} 
    public function getcreatedActivity($selectedDate) : array {return ['css' => "div[title='{$selectedDate}'] > .calendar__event > .calendar__event-name"];} 
    
    public function getActivity($activityParam) : array {return ['xpath' => "//div[@title='{$activityParam['selectedDate']}']//*[text()[contains(., '{$activityParam['activity']}' )]]"];} 
    public function getActivityModal($activity) : array {return ['xpath' => "//div[text()[contains(., '{$activity}' )]]"];} 


    protected $acceptanceTester;

    public function __construct(\Tests\Support\AcceptanceTester $I) 
    {
        $this->acceptanceTester = $I;
        // you can inject other page objects here as well
    }

    public function createActivity(array $activityParam, string $company) : void
    {   $I = $this->acceptanceTester;

        $I->waitAndClick($I->getButtonContains('Manage activities'));
        $I->waitAndClick($this->getAddActivityTitle($activityParam['selectedDate']));
        $I->waitAndClick(self::ADD_ACTIVITY_FIELD_TYPE_BTN);
        $I->scrollAndClick($this->getSelectActivityType($activityParam['activity']));
        $I->waitAndClick(self::ADD_ACTIVITY_FIELD_OWNER_BTN); 
        $I->waitAndClick($this->getSelectActivityCompany($company));       
        $I->waitAndClick(self::ADD_ACTIVITY_FIELD_ADD_BTN);
        $I->waitAndClick(self::ADD_BTN);
        $I->waitForElementVisible($I->getButtonContains('Manage activities'), 60);
        $I->scrollTo($this->getAddActivityTitle($activityParam['selectedDate']));        
        $I->see($activityParam['activity'], $this->getcreatedActivity($activityParam['selectedDate']));
    }
        
    public function navCalendarWDatepicker(string $activity) : void
    {
        $I = $this->acceptanceTester; 

        $I->waitAndClick($I->getButtonContains('Month'));
        $I->waitAndClick(self::DATAPICKER_MONTH);
        $I->waitAndClick(self::DATAPICKER_SELECT_MONTH);
        $I->waitAndClick($this->getActivityModal($activity), 60);
        $I->waitAndClick(self::CANCEL_BUTTON);
    }

    public function deleteActivity(array $activityParam) : void
    {
        $I = $this->acceptanceTester;

        $I->waitAndClick($I->getButtonContains('Year'));
        $I->waitAndClick($I->getButtonContains('Manage activities'));
        $I->waitAndClick($this->getActivity($activityParam));
        $I->waitAndClick(self::EDIT_ACTIVITY_DELETE);
        $I->waitAndClick(self::ADD_BTN);
    }
}
