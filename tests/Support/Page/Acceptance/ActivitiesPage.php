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

    public function dragAndDrop(array $source, array $target ) : void
    {
        $I = $this->acceptanceTester;

        $I->amOnUrl(self::URL);
        $I->waitAndClick(['xpath' => "//button[text()[contains(., 'Manage activities' )]]"], 60);
        $I->waitForElementVisible($source, 60);
        $I->waitForElementVisible($target, 60);

        $I->dragAndDrop($source, $target);

        $I->waitAndClick(['xpath' => "//button[text()[contains(., 'Save' )]]"], 10);
        $I->reloadPage();
    }

    public function createActivity( ) : void
    {
        $I->amOnUrl(self::URL);
        $I->waitAndClick(['xpath' => "//button[text()[contains(., 'Manage activities' )]]"]);
        $I->waitAndClick(['xpath' => "//button//*[text()[contains(., 'Select year' )]]"]); 
               
    }

    public function selectDateByYear( ) : void
    {

    }

    public function selectDateByMonth( ) : void
    {

    }

}
