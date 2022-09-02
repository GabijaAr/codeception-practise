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
    public const FILE_ATTACHED = 'input[data-test-id="file-upload-selector"]';    
    public const FILE_UPLOAD_ALERT = ['css' => 'doc-uploader.shadow rounded fds-card-header']; 
    
    public const MAIN_CARD_HEADER = ['css' => '.ag-header.ag-focus-managed'];
    public const FIRST_ROW_CHECK = ['css' => 'fds-icon-button[icon="more"] > button:first-of-type'];      
    public const FIRST_ROW = ['css' => '.ag-pinned-right-cols-container > div[role="row"]:first-of-type']; 
    public const FIRST_ROW_MORE_BUTTON = ['css' => '.ag-pinned-right-cols-container > div[role="row"]:first-child fds-icon-button[icon="more"] > button'];       
    public const MORE_DELETE = ['css' => 'fds-dropdown-menu-item[data-gtm-id="file-actions-delete"] > button'];  
    public const CONFIRM_DELETE = ['css' => 'button[data-gtm-id="confirm-confirmation-modal"]'];
    
    public const SORT_BY_ADDED = ['css' => 'div[col-id="createdAt"]'];
    public const NEW_DOCUMENT_AREA_BUTTON = ['css' => 'fds-button[data-test-id="sidebar-create-document-area"]'];  
    public const DOCUMENT_AREA_NAME_INPUT = ['css' => 'fds-text-field > input[placeholder="Document area name"]'];
    public const PRIMARY_BUTTON = ['css' => 'button.btn-primary'];

    protected $acceptanceTester;

    public function __construct(\Tests\Support\AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
    }

    public function redirectToDocumentsPage(
        string $username, 
        string $password, 
        $loginPage) : void
    {
        $I = $this->acceptanceTester;

        $loginPage->login($username, $password);
        $I->waitForElementVisible(PortalPage::PORTAL_NEWS_SECT, 120);
        $I->amOnUrl(self::URL);
    }

    public function fileUpload(string $file) : void
    {
        $I = $this->acceptanceTester;

        $I->amOnUrl(self::URL);
        $I->waitForElementVisible(self::DRIVE_NAV_ASIDE, 120);
        $I->waitAndClick(self::FILE_TO_CONSULT_BUTTON);
        $I->waitForElementVisible(self::MAIN_CARD, 120); 
        $I->waitAndClick(self::FILE_UPLOAD_BUTTON);        
    
        $I->attachFile(self::FILE_ATTACHED, $file);
        $I->waitForElementNotVisible(self::FILE_UPLOAD_ALERT, 120);
        $I->reloadPage();
        $I->waitForElementVisible(self::MAIN_CARD_HEADER, 120); 
    }

    public function fileDelete() : void
    {
        $I = $this->acceptanceTester;

        $I->amOnUrl(self::URL_FILE_DROP);
        $I->waitForElementVisible(self::MAIN_CARD_HEADER, 120);  
        $I->click(self::SORT_BY_ADDED);
        $I->click(self::SORT_BY_ADDED);
        $I->click(self::FIRST_ROW_MORE_BUTTON);     
        $I->click(self::MORE_DELETE);
        $I->click(self::CONFIRM_DELETE);
        $I->waitForElementClickable(self::FIRST_ROW_CHECK, 120);
    }

    public function grantAccessToDirectory($documentAreaName) : void
    {
        $I = $this->acceptanceTester;

        $I->amOnUrl('https://documents-develop-devdb.staging.cozone.com/ui/recent'); 
        $I->waitAndClick(Locator::contains('a', "{$documentAreaName}"));
        $I->waitAndClick('#users-tab');
        $I->waitAndClick(Locator::contains('fds-button button.btn-text-flush', ' Add users '));        
        $I->waitAndClick(Locator::contains('button', 'Select users'));
        $I->waitAndClick(Locator::contains('fds-selector-menu-checkbox label', ' Acc3 test company '));
        $I->waitAndClick(Locator::contains('fds-button > button.btn-primary', ' Add users '));
        $I->waitAndClick(Locator::contains('fds-selector > button', 'Viewer'));
        $I->click(Locator::contains('fds-card-footer button', 'Save'));
        $I->waitForElementNotVisible('.alert-success', 60);
    }
    
}