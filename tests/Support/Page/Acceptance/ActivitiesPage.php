<?php

declare(strict_types=1);

namespace Tests\Support\Page\Acceptance;

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

    function getSourceActivity($activityTipe) : array {return ['xpath' => "//fds-layout-aside//fds-tag[@draggable='true']//*[text()[contains(., '{$activityTipe}' )]]"];}
    function getTargetCalendarSlot($dataKey) : array {return ['xpath' => "//fds-layout-main//div[@data-key='{$dataKey}']"];}

    protected $acceptanceTester;

    public function __construct(\Tests\Support\AcceptanceTester $I) 
    {
        $this->acceptanceTester = $I;
        // you can inject other page objects here as well
    }

    public function createActivity( ) : void
    {   $I = $this->acceptanceTester;
        // url
        $I->waitAndClick(['xpath' => "//button[text()[contains(., 'Manage activities' )]]"]);
        $I->waitAndClick(['css' => '[title="Wed 20, June"]']);
        $I->waitAndClick(['xpath' => "//button[text()[contains(., 'Approve time reports' )]]"]);
        $I->scrollTo(['xpath' => "//fds-selector-menu//button[text()[contains(., 'Operational meeting' )]]"]);
        $I->click(['xpath' => "//fds-selector-menu//button[text()[contains(., 'Operational meeting' )]]"]);
        $I->waitAndClick(['xpath' => "//act-activity-modal//button[text()[contains(., 'Save' )]]"]);
        $I->waitAndClick(['xpath' => "//fds-layout-main//button[text()[contains(., 'Save' )]]"]);
        $I->see('Operational meeting',['css' => 'div[title="Wed 20, June"] > .calendar__event > .calendar__event-name']);
    }
          
    public function deleteActivity( ) : void
    {
        $I = $this->acceptanceTester;

        $I->reloadPage();
        $I->waitAndClick(['xpath' => "//button[text()[contains(., 'Manage activities' )]]"]);
        $I->waitAndClick(['xpath' => "//div[@title='Wed 20, June']/div[@class='calendar__event text-truncate border-light-blue-800']//*[text()[contains(., 'Operational meeting' )]]"]);
        $I->waitAndClick(['xpath' => "//act-activity-modal//button[text()[contains(., 'Delete activity' )]]"]);
        $I->waitAndClick(['xpath' => "//fds-layout-main//button[text()[contains(., 'Save' )]]"]);
    }

}
