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

    public const URL = 'https://documents-develop-devdb.staging.cozone.com/ui/recent'; //
    public const URL_FILE_DROP = 'https://documents-develop-devdb.staging.cozone.com/ui/file-drop'; //

    public const NAV_SIDEBAR = ['css' => 'fds-layout-aside']; 
    public const SIDEBAR_CURRENT_COMPANY = ['css' => 'fds-tree-item[data-gtm-id="sidebar-link-current-company"] > a']; 
    public const DIRECTORY_MORE_BUTTON = ['css' => '#more-actions'];    
    public const FILE_ATTACH = ['css' => 'input[data-test-id="file-upload-selector"]']; 
    public const BUTTON_UPLOAD_FILE = ['xpath' => "//button[text()[contains(., 'Upload file' )]]"];  
    public const FILE_UPLOAD_BUTTON = ['css' => 'fds-dropdown-menu-item[data-gtm-id="directory-upload-files"] > button']; 
    public const TEXT_FIELD_INPUT = ['css' => 'fds-text-field > input'];       
    public const COMPANY_CONTENT_ACTIONS_ROW = ['css' => 'div.ag-pinned-right-cols-container'];
    public const DIRECTORY_DELETE_BUTTON = ['css' => 'fds-dropdown-menu-item[data-gtm-id="directory-actions-delete-directory"] > button'];
    public const FILE_TO_CONSULT_BUTTON = ['css' => 'fds-tree-item a[href="/ui/file-drop"]'];
    public const MAIN_CARD = ['css' => 'doc-file-drop fds-card'];
    public const FILE_UPLOAD_ALERT = ['css' => 'doc-uploader.shadow rounded fds-card-header'];

    public const NAV_SETUP_STRUCTURE = ['css' => 'a[href="/ui/default-structure-setup"]'];
    public const USER_TAB = ['css' =>'#users-tab'];
    public const DIRECTORY_FINANCE_REPORTS = ['xpath' => "//a[text()[contains(., 'Finance Reports')]]"];
    public const DIRECTORY_FROM_AZETS = ['xpath' => "//a[text()[contains(., 'From Azets')]]"];
    public const DIRECTORY_TO_AZETS = ['xpath' => "//a[text()[contains(., 'To Azets')]]"];
    public const DIRECTORY_HR_REPORTS = ['xpath' => "//a[text()[contains(.,'HR Reports')]]"];

    public const STRUCTURE_FIELD_COUNTRY = ['css' => 'fds-selector-field[formcontrolname="country"]'];
    public const STRUCTURE_FIELD_YEAR = ['css' => 'fds-selector-field[formcontrolname="year"]'];
    public const STRUCTURE_FIELD_LANGUAGE = ['css' => 'fds-selector-field[formcontrolname="language"]'];
    public const STRUCTURE_FIELD_DOC_AREAS = ['css' => 'fds-selector-field[formcontrolname="documentAreas"]'];    
    public const STRUCTURE_FIELD_COMPANIES = ['css' => 'fds-selector-field[formcontrolname="companies"]']; 
    public const STRUCTURE_CREATE = [ 'xpath' => '//button[text()[contains(., "Create structure")]]'];
    public const STRUCTURE_COUTRY_SWEDEN = [ 'xpath' => '//button[text()[contains(., "Sweden")]]'];
    public const STRUCTURE_YEAR_2024 = [ 'xpath' => '//button[text()[contains(., "2024")]]'];
    public const STRUCTURE_LANGUAGE_EN = [ 'xpath' => '//button[text()[contains(., "EN")]]'];
    public const STRUCTURE_DOC_AREAS_HR = [ 'xpath' => '//input/following-sibling::label[text()[contains(., "HR Reports")]]'];
    public const STRUCTURE_DOC_AREAS_FINANCE = [ 'xpath' => '//input/following-sibling::label[text()[contains(., "Finance Reports")]]']; 
    public const STRUCTURE_COMPANIES_INPUT = ['css' => 'fds-selector-field[formcontrolname="companies"] input'];

    public const MAIN_CARD_HEADER = ['css' => '.ag-header.ag-focus-managed'];
    public const FIRST_ROW_CHECK = ['css' => 'fds-icon-button[icon="more"] > button:first-of-type'];      
    public const FIRST_ROW = ['css' => '.ag-pinned-right-cols-container > div[role="row"]:first-of-type']; 
    public const MORE_DELETE = ['css' => 'fds-dropdown-menu-item[data-gtm-id="file-actions-delete"] > button'];  
    public const CONFIRM_DELETE = ['css' => 'button[data-gtm-id="confirm-confirmation-modal"]'];
    
    public const SORT_BY_ADDED = ['css' => 'div[col-id="createdAt"]'];
    public const NEW_DOCUMENT_AREA_BUTTON = ['css' => 'fds-button[data-test-id="sidebar-create-document-area"]'];  
    public const DOCUMENT_AREA_NAME_INPUT = ['css' => 'fds-text-field > input[placeholder="Document area name"]'];
    public const BUTTON_SAVE_NEW_DOC = ['xpath' => "//button[text()[contains(.,'Save')]]"];
    public const BUTTON_SELECT_APPROVERS = ['xpath' => "//button[text()[contains(.,'Select approvers')]]"];
    public const BUTTON_REQUEST_APPROVAL = ['xpath' => "//button[text()[contains(.,'Request for approval')]]"];
    public const BUTTON_APPROVE = ['xpath' => "//button[text()[contains(.,'Approve')]]"];
    public const RECENT_FROM_CONSULTANT = ['xpath' => "//button[text()[contains(., 'From consultant' )]]"];
    public const RECENT_TO_CONSULTANT = ['xpath' => "//button[text()[contains(., 'To consultant' )]]"];   
    
    public const REQUEST_SELECT_APPROVERS = ['css' => 'fds-selector-menu-checkbox > label'];
    public const BREADCRUMBS_PATH = ['css' => "a[data-gtm-id='file-breadcrumbs-cell-directory']:first-of-type"];   
    public const TOOLTIP_INNER_DETAILED = ['css' => 'body > div .tooltip-inner'];    
    public const TOOLTIP_INNER = ['css' => '.tooltip-inner']; 

    public const ALERT_SUCCESS = ['css' => '.alert-success'];
    

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
    

    public function fileUpload(array $sharedData, string $companyName) : void
    {
        $I = $this->acceptanceTester;

        $I->amOnUrl(self::URL);
        $I->waitForElementVisible(self::NAV_SIDEBAR, 120);
        $I->waitAndClick(['xpath' => "//a//span[text()[contains(., '{$companyName}' )]]"], 60);        
        $I->waitAndClick(['css' => "[title='{$sharedData['documentAreaName']}'] a"], 60);
        $I->waitAndClick(self::DIRECTORY_MORE_BUTTON, 60);  
        $I->waitAndClick(self::FILE_UPLOAD_BUTTON, 60);
        $I->attachFile(self::FILE_ATTACH, "{$sharedData['file']}");
        $I->waitForElementVisible(self::TEXT_FIELD_INPUT, 60);
        $I->fillField(self::TEXT_FIELD_INPUT, "{$companyName}");
        $I->waitAndClick(['xpath' => "//button[text()[contains(., 'Upload files' )]]"], 60);        
        $I->reloadPage();
    }

    public function fileDelete(string $documentAreaName, int $fileId) : void
    {
        $I = $this->acceptanceTester;

        $I->amOnUrl(self::URL);
        $I->waitForElementVisible(self::NAV_SIDEBAR, 120);
        $I->waitAndClick(['xpath' => "//fds-tree-item/a[text()[contains(., '{$documentAreaName}' )]]"], 60);        
        $I->waitAndClick("div[row-id='{$fileId}'] fds-icon-button[data-gtm-id='file-actions-show-more-actions'] button ");     
        $I->click(self::MORE_DELETE);
        $I->click(self::CONFIRM_DELETE);
        $I->waitForElementClickable(self::FIRST_ROW_CHECK, 120);
    }

    public function createDocumentArea(string $documentAreaName) : void
    {
        $I = $this->acceptanceTester;
                
        $I->amOnUrl(self::URL);
        $I->waitForElementVisible(self::NAV_SIDEBAR, 120);
        $I->waitAndClick(self::NEW_DOCUMENT_AREA_BUTTON);
        $I->waitAndFill(self::DOCUMENT_AREA_NAME_INPUT, "{$documentAreaName}");
        $I->waitAndClick(self::BUTTON_SAVE_NEW_DOC, 60);
        $I->waitForElementVisible(['xpath' => "//doc-current-directory-header/fds-card-title[text()[contains(.,'Doc Area')]]"], 60);
    }

    public function directoryDelete(string $documentAreaName, int $directoryId) : void
    {
        $I = $this->acceptanceTester;

        $I->amOnUrl(self::URL_FILE_DROP);
        $I->waitForElementVisible(self::MAIN_CARD_HEADER, 120);  
        $I->waitAndClick(self::SIDEBAR_CURRENT_COMPANY, 60);
        $I->waitForElementVisible(self::COMPANY_CONTENT_ACTIONS_ROW, 60);
        $I->click(['css' => "div[row-id='{$directoryId}'] fds-icon-button[icon='more'] > button"]);   
        $I->click(self::DIRECTORY_DELETE_BUTTON);
        $I->click(self::CONFIRM_DELETE);
        $I->reloadPage();
        $I->dontSeeElement(['css' => "div.ag-row-even[row-id='{$directoryId}']"]);
    }


    public function grantAccessToDirectory(
        string $documentAreaName, 
        array $user, 
        string $companyName,
        string $permission) : void
    {
        $I = $this->acceptanceTester;

        $I->amOnUrl(self::URL); 

        $I->waitAndClick(['xpath' => "//a//span[text()[contains(., '{$companyName}')]]"], 60);        
        $I->waitAndClick(['xpath' => "//a//span[text()[contains(., '{$documentAreaName}')]]"], 60);
        $I->waitAndClick(self::USER_TAB, 60);
        $I->waitAndClick(['xpath' => "//button[text()[contains(., 'Add users')]]"], 60);               

        $I->waitAndClick(['css' => '[formcontrolname="company"]'], 60);  
        $I->waitForElementVisible(['xpath' => "//fds-selector-menu-item/button"], 60);
        $I->fillField(['css' => '[placeholder="Type to search"]'], "{$user['company']}");
        $I->waitAndClick(['xpath' => "//fds-selector-menu-item[1]/button[text()[contains(., '{$user['company']}')]]"], 60);  

        $I->waitAndClick(['xpath' => "//button[text()[contains(., 'Select users')]]"], 60);
        $I->waitForElementVisible(['css' => 'fds-selector-menu-checkbox:first-of-type'], 60);
        $I->fillField(['css' => '[placeholder="Type to search"]'], "{$user['user']}");
        $I->waitForElementVisible([ 'xpath' => "//label[text()[contains(., '{$user['user']}')]]"], 60);        
        $I->selectOption([ 'css' => 'input'], ['xpath' => "//label[text()[contains(., '{$user['user']}')]]"]);

        $I->scrollTo([ 'xpath' => "//doc-share-permissions//button[text()[contains(., 'Add users')]]"],0, 100);
        $I->waitAndClick([ 'xpath' => "//doc-share-permissions//button[text()[contains(., 'Add users')]]"], 60);       

        $I->waitAndClick([ 'xpath' => "//button[text()[contains(., '{$permission}')]]"], 60);        


        $I->click(['xpath' => "//button[text()[contains(., 'Save')]]"]);        
        $I->waitForElementNotVisible(self::ALERT_SUCCESS, 60);
    }

    public function deleteDirectoryApi(int $directoryId) : void
    {
        $I = $this->acceptanceTester;

        $I->amOnUrl(self::URL_FILE_DROP);
        $I->waitForElementVisible(self::MAIN_CARD_HEADER, 120);  
        $I->waitAndClick(self::SIDEBAR_CURRENT_COMPANY, 60);
        $I->waitForElementVisible(self::COMPANY_CONTENT_ACTIONS_ROW, 60);
        $I->click(['css' => "div[row-id='{$directoryId}'] fds-icon-button[icon='more'] > button"]);   
        $I->click(['css' => 'fds-dropdown-menu-item[data-gtm-id="directory-actions-delete-directory"] > button']);
        $I->click(self::CONFIRM_DELETE);
        $I->reloadPage();
        $I->dontSeeElement(['css' => "div.ag-row-even[row-id='{$directoryId}']"]);
    }
}
