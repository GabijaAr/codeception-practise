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

    public const SIDEBAR_CURRENT_COMPANY = ['css' => 'fds-tree-item[data-gtm-id="sidebar-link-current-company"] > a'];
    public const DIRECTORY_MORE_BUTTON = ['css' => '#more-actions'];    
    public const FILE_ATTACH = ['css' => 'input[data-test-id="file-upload-selector"]']; 
    public const CONSULT_FILE_UPLOAD_BUTTON = ['xpath' => "//button[text()[contains(., 'Upload file' )]]"];  
    public const FILE_UPLOAD_BUTTON = ['css' => '[data-gtm-id="directory-upload-files"] > button'];
    public const TEXT_FIELD_INPUT = ['css' => 'fds-text-field > input'];       
    public const DIRECTORY_DELETE_BUTTON = ['css' => '[data-gtm-id="directory-actions-delete-directory"] > button'];
    public const BUTTON_FILE_SEND_REQUEST = ['css' => 'fds-icon.freshicon-send'];
    public const BUTTON_FILE_DOWNLOAD = ['xpath' => "//button/fds-icon[@class='freshicon freshicon-download']"];
    public const FILE_FRIEND_MORE_ACTIONS = ['css' => "fds-icon-button[data-gtm-id='file-actions-show-more-actions'] button"];
    public const BUTTON_ADD_USERS = [ 'xpath' => "//doc-share-permissions//button[text()[contains(., 'Add users')]]"];      

    public const NAV_SETUP_STRUCTURE = ['css' => 'a[href="/ui/default-structure-setup"]'];
    public const USER_TAB = ['css' =>'#users-tab'];
    public const DIRECTORY_FINANCE_REPORTS = ['xpath' => "//fds-layout-aside//a//*[text()[contains(., 'Finance Reports')]]"];
    public const DIRECTORY_FROM_AZETS = ['xpath' => "//a[text()[contains(., 'From Azets')]]"];
    public const DIRECTORY_TO_AZETS = ['xpath' => "//a[text()[contains(., 'To Azets')]]"];
    public const DIRECTORY_HR_REPORTS = ['xpath' => "//a[text()[contains(.,'HR Reports')]]"];
    public const STRUCTURE_FIELD_COUNTRY = ['css' => '[formcontrolname="country"]']; 
    public const STRUCTURE_FIELD_YEAR = ['css' => '[formcontrolname="year"]']; 
    public const STRUCTURE_FIELD_LANGUAGE = ['css' => '[formcontrolname="language"]']; 
    public const STRUCTURE_FIELD_DOC_AREAS = ['css' => '[formcontrolname="documentAreas"]']; 
    public const STRUCTURE_FIELD_COMPANIES = ['css' => '[formcontrolname="companies"]']; 
    public const STRUCTURE_DOC_AREAS_HR = [ 'xpath' => '//input/following-sibling::label[text()[contains(., "HR Reports")]]'];
    public const STRUCTURE_DOC_AREAS_FINANCE = [ 'xpath' => '//input/following-sibling::label[text()[contains(., "Finance Reports")]]']; 
    public const STRUCTURE_COMPANIES_INPUT = ['css' => 'fds-selector-field[formcontrolname="companies"] input'];

    public const FIRST_ROW_CHECK = ['css' => 'fds-icon-button[icon="more"] > button:first-of-type'];      
    public const MORE_DELETE = ['css' => '[data-gtm-id="file-actions-delete"] > button'];
    public const CONFIRM_DELETE = ['css' => 'button[data-gtm-id="confirm-confirmation-modal"]'];
    public const NEW_DOCUMENT_AREA_BUTTON = ['css' => '[data-test-id="sidebar-create-document-area"]'];
    public const DOCUMENT_AREA_NAME_INPUT = ['css' => 'fds-text-field > input[placeholder="Document area name"]'];
    public const BUTTON_SAVE_NEW_DOC = ['xpath' => "//button[text()[contains(.,'Save')]]"];
    public const BUTTON_SELECT_APPROVERS = ['xpath' => "//button[text()[contains(.,'Select approvers')]]"];
    public const BUTTON_REQUEST_APPROVAL = ['xpath' => "//button[text()[contains(.,'Request for approval')]]"];
    public const BUTTON_APPROVE = ['xpath' => "//button[text()[contains(.,'Approve')]]"];
    public const RECENT_FROM_CONSULTANT = ['xpath' => "//button[text()[contains(., 'From consultant' )]]"];
    public const RECENT_TO_CONSULTANT = ['xpath' => "//button[text()[contains(., 'To consultant' )]]"];
    public const BUTTON_DELETE_DIRECTORY = ['css' => '[data-gtm-id="directory-actions-delete-directory"] > button'];
    public const FORM_COMPANY = ['css' => '[formcontrolname="company"]'];
    public const BUTTON_ITEM = ['css' => "fds-selector-menu-item button"];
    public const SEARCH_FIELD = ['css' => '[placeholder="Type to search"]'];    
    public const INPUT = [ 'css' => 'input'];    
    public const CHECKBOX_FIRST_OF_TYPE = ['css' => 'fds-selector-menu-checkbox:first-of-type']; 
    public const REQUEST_SELECT_APPROVERS = ['css' => 'fds-selector-menu-checkbox > label'];
    public const BREADCRUMBS_PATH = ['css' => "[data-gtm-id='file-breadcrumbs-cell-directory']:first-of-type"];
    public const TOOLTIP_INNER = ['css' => '.tooltip-inner']; 
    public const ALERT_SUCCESS = ['css' => '.alert-success'];

    public function getButtonContains($text) : array {return ['xpath' => "//button[text()[contains(.,'{$text}')]]"];}
    public function getLabelContains($text) : array {return ['xpath' => "//label[text()[contains(.,'{$text}')]]"];}
    public function getLinkContains($text) : array {return ['xpath' => "//a[text()[contains(.,'{$text}')]]"];}  
    public function getSpanContains($text) : array {return ['xpath' => "//a//span[text()[contains(., '{$text}')]]"];}  

    public function getDirectoryShareWMe($parentDirectoryId) : array {return ['css' => "a.tree-node.tree-node--level-1[href='/ui/directory/{$parentDirectoryId}']"];}
    public function getDocumentNameTitle($docName) : array {return ['css' => "[title='{$docName}'] a"];}    
    public function getButtonActionsWId($fileId) : array {return [ 'css' => "div[row-id='{$fileId}'] fds-icon-button[data-gtm-id='file-actions-show-more-actions'] button "];}  
    public function getDocumentIdButtonMore($directoryId) : array {return ['css' => "div[row-id='{$directoryId}'] fds-icon-button[icon='more'] > button"];}    
    public function getCardDirectoryName($directoryName) : array {return ['xpath' => "//doc-current-directory-header/fds-card-title[text()[contains(.,'{$directoryName}')]]"];}       
    public function getCardDirectoryRow($directoryId) : array {return ['css' => "div.ag-row-even[row-id='{$directoryId}']"];}      
    public function getSelectCompanyButton($userCompany) : array {return ['xpath' => "//fds-selector-menu-item[1]/button[text()[contains(., '{$userCompany}')]]"];}      
    public function getRelativePath($relativePath) : array {return ['xpath' => "//doc-directory-tree//a//span[text()[contains(.,'{$relativePath}')]]"];}    
    
    public function getCheckboxUser($user) : array {return ['xpath' => "//fds-selector-menu-checkbox/label[text()[contains(.,'{$user}')]]"];}      
    public function getButtonSend($fileId) : array {return ['css' => "div[row-id='{$fileId}'] fds-icon-button[icon='send'] button"];}
    public function getButtonWFunction($fileId, $funct) : array {return ['css' => "div[row-id='{$fileId}'] fds-icon-button[icon='{$funct}'] button "];}         

    public function getDirectoryAside($companyName) : array {return ['xpath' => "//fds-layout-aside//a//*[text()[contains(.,'{$companyName}')]]"];}  

    protected $acceptanceTester;

    public function __construct(\Tests\Support\AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
    }

    public function fileUpload(array $sharedData, string $companyName) : void
    {
        $I = $this->acceptanceTester;

        $I->amOnUrl(self::URL);
        $I->waitAndClick($this->getSpanContains($companyName));        
        $I->waitAndClick($this->getDocumentNameTitle($sharedData['documentAreaName']));        
        $I->waitAndClick(self::DIRECTORY_MORE_BUTTON);  
        $I->waitAndClick(self::FILE_UPLOAD_BUTTON);
        $I->attachFile(self::FILE_ATTACH, "{$sharedData['file']}");
        $I->waitAndFill(self::TEXT_FIELD_INPUT, "{$companyName}");
        $I->waitAndClick($this->getButtonContains('Upload files'));        
        $I->reloadPage();
    }

    public function fileDelete(string $documentAreaName, int $fileId) : void
    {
        $I = $this->acceptanceTester;

        $I->amOnUrl(self::URL);
        $I->waitAndClick(self::SIDEBAR_CURRENT_COMPANY);
        $I->waitAndClick($this->getLinkContains($documentAreaName)); 
        $I->waitAndClick($this->getButtonActionsWId($fileId));
        $I->click(self::MORE_DELETE);
        $I->click(self::CONFIRM_DELETE);
        $I->waitForElementClickable(self::FIRST_ROW_CHECK, 120);
    }

    public function createDocumentArea(string $documentAreaName) : void
    {
        $I = $this->acceptanceTester;
                
        $I->amOnUrl(self::URL);
        $I->waitAndClick(self::NEW_DOCUMENT_AREA_BUTTON);
        $I->waitAndFill(self::DOCUMENT_AREA_NAME_INPUT, "{$documentAreaName}");
        $I->waitAndClick(self::BUTTON_SAVE_NEW_DOC);
        $I->waitForElementVisible($this->getCardDirectoryName('Doc Area'), 60);
    }

    public function directoryDelete(string $documentAreaName, int $directoryId) : void
    {
        $I = $this->acceptanceTester;

        $I->amOnUrl(self::URL_FILE_DROP);
        $I->waitAndClick(self::SIDEBAR_CURRENT_COMPANY);
        $I->waitAndClick($this->getDocumentIdButtonMore($directoryId));   
        $I->waitAndClick(self::DIRECTORY_DELETE_BUTTON);
        $I->waitAndClick(self::CONFIRM_DELETE);
        $I->reloadPage();
        $I->dontSeeElement($this->getCardDirectoryRow($directoryId));
    }


    public function grantAccessToDirectory(
        string $documentAreaName, 
        array $user, 
        string $companyName,
        string $permission) : void
    {
        $I = $this->acceptanceTester;

        $I->amOnUrl(self::URL); 

        $I->waitAndClick($this->getSpanContains($companyName));
        $I->waitAndClick($this->getSpanContains($documentAreaName));
        $I->waitAndClick(self::USER_TAB);
        $I->waitAndClick($this->getButtonContains('Add users'));               
        
        $I->waitAndClick(self::FORM_COMPANY);  
        $I->waitForElementVisible(self::BUTTON_ITEM, 60);
        $I->fillField(self::SEARCH_FIELD, "{$user['company']}");
        $I->waitAndClick($this->getSelectCompanyButton($user['company']));  
        $I->waitAndClick($this->getButtonContains('Select users'));
        $I->waitForElementVisible(self::CHECKBOX_FIRST_OF_TYPE, 60);
        $I->fillField(self::SEARCH_FIELD, "{$user['user']}");
        $I->waitForElementVisible($this->getLabelContains($user['user']), 60); 
        $I->selectOption(self::INPUT, $this->getLabelContains($user['user']));
        $I->scrollTo(self::BUTTON_ADD_USERS,0, 100);
        $I->waitAndClick(self::BUTTON_ADD_USERS);       
        $I->waitAndClick($this->getButtonContains($permission));        
        
        $I->click($this->getButtonContains('Save'));        
        $I->waitForElementNotVisible(self::ALERT_SUCCESS, 60);
    }

    public function deleteDirectoryApi(int $directoryId) : void
    {
        $I = $this->acceptanceTester;

        $I->amOnUrl(self::URL_FILE_DROP);
        $I->waitAndClick(self::SIDEBAR_CURRENT_COMPANY);
        $I->waitAndClick($this->getDocumentIdButtonMore($directoryId)); 
        $I->waitAndClick(self::BUTTON_DELETE_DIRECTORY);
        $I->waitAndClick(self::CONFIRM_DELETE);
        $I->reloadPage();
        $I->dontSeeElement($this->getCardDirectoryRow($directoryId));
    }
}
