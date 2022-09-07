<?php

declare(strict_types=1);

namespace Tests\Support\Page\Acceptance;

use Tests\Support\AcceptanceTester;
use Codeception\Util\Locator;
use Tests\Support\Page\Acceptance\LoginPage;
use Tests\Support\Page\Acceptance\PortalPage;

class DocumentsPage
{
    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public $usernameField = '#username';
     * public $formSubmitButton = "#mainForm input[type=submit]";
     */

    /**
     * @var \Tests\Support\AcceptanceTester;
     */
    public const URL = 'https://documents-develop-devdb.staging.cozone.com/ui/recent';
    public const URL_FILE_DROP = 'https://documents-develop-devdb.staging.cozone.com/ui/file-drop';

    public const DRIVE_NAV_ASIDE = ['css' => 'fds-layout-aside'];
    public const FILE_TO_CONSULT_BUTTON = ['css' => 'fds-tree-item a[href="/ui/file-drop"]'];
    public const FILE_UPLOAD_BUTTON = ['css' => 'doc-file-selector > fds-button'];
    public const MAIN_CARD = ['css' => 'doc-file-drop fds-card'];
    public const FILE_ATTACHED = 'input[data-test-id="file-upload-selector"]';    //
    public const FILE_UPLOAD_ALERT = ['css' => 'doc-uploader.shadow rounded fds-card-header']; 

    
    public const MAIN_CARD_HEADER = ['css' => '.ag-header.ag-focus-managed'];
    public const FIRST_ROW_CHECK = ['css' => 'fds-icon-button[icon="more"] > button:first-of-type'];      
    public const FIRST_ROW = ['css' => '.ag-pinned-right-cols-container > div[role="row"]:first-of-type']; 
    public const FIRST_ROW_MORE_BUTTON = ['css' => '.ag-pinned-right-cols-container > div[row-id="2931198"] fds-icon-button[icon="more"] > button'];       
    public const MORE_DELETE = ['css' => 'fds-dropdown-menu-item[data-gtm-id="file-actions-delete"] > button'];  
    public const CONFIRM_DELETE = ['css' => 'button[data-gtm-id="confirm-confirmation-modal"]'];
    
    public const SORT_BY_ADDED = ['css' => 'div[col-id="createdAt"]'];
    public const NEW_DOCUMENT_AREA_BUTTON = ['css' => 'fds-button[data-test-id="sidebar-create-document-area"]'];  
    public const DOCUMENT_AREA_NAME_INPUT = ['css' => 'fds-text-field > input[placeholder="Document area name"]'];
    public const PRIMARY_BUTTON = ['css' => 'button.btn-primary'];

    public const SIDEBAR_DOCUMENT_AREA_SELECTOR = ['css' => 'fds-tree-item[data-test-id="sidebar-current-company"] a'];
    public const SHOW_MORE_ACTIONS_BUTTON = [];

    protected $acceptanceTester;

    public function __construct(\Tests\Support\AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
    }

    public function redirectToDocumentsPage(array $user, $loginPage) : void
    {
        $I = $this->acceptanceTester;

        $loginPage->login($user);
        $I->waitForElementVisible(PortalPage::PORTAL_NEWS_SECT, 120);
        $I->amOnUrl(self::URL);
    }

    public function fileUpload(string $file, string $documentAreaName) : void
    {
        $I = $this->acceptanceTester;

        $I->amOnUrl(self::URL);
        $I->waitForElementVisible(self::DRIVE_NAV_ASIDE, 120);

        $I->waitAndClick(Locator::contains('fds-tree-item a', 'Test Company 09/2022'),60);
        $I->waitAndClick("doc-file-name-cell > div[title='{$documentAreaName}'] a",60);
        $I->waitAndClick(['css' => '#more-actions'], 60);  
        $I->waitAndClick(['css' => 'fds-dropdown-menu-item[data-gtm-id="directory-upload-files"] > button'], 60);
        $I->attachFile('input[data-test-id="file-upload-selector"]', $file);

        $I->waitForElementVisible(['css' => 'fds-text-field > input'], 60);
        $I->fillField(['css' => 'fds-text-field > input'], 'Test Company 09/2022');
        $I->waitAndClick(Locator::contains('button', " Upload files  "), 60);
    }

    public function fileDelete($documentAreaName, $fileId) : void
    {
        $I = $this->acceptanceTester;

        $I->amOnUrl(self::URL);
        $I->waitForElementVisible(self::DRIVE_NAV_ASIDE, 120);
        $I->waitAndClick(Locator::contains('fds-tree-item[icon="document-area"] > a', $documentAreaName ), 60);
        $I->waitAndClick("div[row-id='{$fileId}'] fds-icon-button[data-gtm-id='file-actions-show-more-actions'] button ");     
        $I->click(self::MORE_DELETE);
        $I->click(self::CONFIRM_DELETE);
        $I->waitForElementClickable(self::FIRST_ROW_CHECK, 120);
    }

    public function createDocumentArea($documentAreaName)
    {
        $I = $this->acceptanceTester;
                
        $I->amOnUrl(self::URL);
        $I->waitForElementVisible(self::DRIVE_NAV_ASIDE, 120);
        $I->waitAndClick(self::NEW_DOCUMENT_AREA_BUTTON);
        $I->waitAndFill(self::DOCUMENT_AREA_NAME_INPUT, "{$documentAreaName}");
        $I->waitAndClick(self::PRIMARY_BUTTON);
    }
// 
    public function directoryDelete($documentAreaName, $directoryId) : void
    {
        $I = $this->acceptanceTester;

        $I->amOnUrl(self::URL_FILE_DROP);
        $I->waitForElementVisible(self::MAIN_CARD_HEADER, 120);  
        $I->waitAndClick(['css' => 'fds-tree-item[data-gtm-id="sidebar-link-current-company"] > a'], 60);
        $I->waitForElementVisible(['css' => 'div.ag-pinned-right-cols-container'], 60);
        $I->click(['css' => "div[row-id='{$directoryId}'] fds-icon-button[icon='more'] > button"]);   
        $I->click(['css' => 'fds-dropdown-menu-item[data-gtm-id="directory-actions-delete-directory"] > button']);
        $I->click(self::CONFIRM_DELETE);
        $I->reloadPage();
        $I->dontSeeElement(['css' => "div.ag-row-even[row-id='{$directoryId}']"]);
    }

    public function grantAccessToDirectory($documentAreaName, $user) : void
    {
        $I = $this->acceptanceTester;

        $I->amOnUrl(self::URL); 
        $I->waitAndClick(Locator::contains('a', "{$documentAreaName}"), 60);
        $I->waitAndClick(['css' =>'#users-tab'], 12);
        $I->waitAndClick(Locator::contains('fds-button button.btn-text-flush', ' Add users '), 60);        
        $I->waitAndClick(Locator::contains('button', 'Select users'), 60);
        $I->waitForElementVisible(Locator::contains('label.custom-control-label', $user), 60);
        $I->checkOption(Locator::contains('label.custom-control-label', $user));
        $I->waitAndClick(Locator::contains('fds-button > button.btn-primary', ' Add users '), 60);
        $I->waitAndClick(Locator::contains('fds-selector > button', 'Viewer'), 60);
        $I->click(Locator::contains('fds-card-footer button', 'Save'));
        $I->waitForElementNotVisible(['css' => '.alert-success'], 60);
    }

    public function documentAreaDelete($directoryName) : void
    {
        $I = $this->acceptanceTester;

        $I->amOnUrl(self::URL_FILE_DROP);
        $I->waitForElementVisible(self::MAIN_CARD_HEADER, 120);  
        $I->waitAndClick(['css' => 'fds-tree-item[data-gtm-id="sidebar-link-current-company"] > a'], 60);
        $I->waitForElementVisible(['css' => 'div.ag-pinned-right-cols-container'], 60);
        $I->click(['css' => "div[row-id='{$directoryName}'] fds-icon-button[icon='more'] > button"]);   
        $I->click(['css' => 'fds-dropdown-menu-item[data-gtm-id="directory-actions-delete-directory"] > button']);
        $I->click(self::CONFIRM_DELETE);
        $I->reloadPage();
        $I->dontSeeElement(['css' => "div.ag-row-even[row-id='{$directoryName}']"]);
    }
}
