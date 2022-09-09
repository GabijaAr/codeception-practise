<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;
use Codeception\Util\Shared\Asserts;
use Codeception\Util\Locator;
use Codeception\Attribute\Incomplete;
use Tests\Support\Page\Acceptance\DocumentsPage;
use Tests\Support\Page\Acceptance\LoginPage;
use Tests\Support\Page\Acceptance\PortalPage;
use Tests\Support\Helper\PasswordHelper;
use Tests\Support\Helper\DocumentsHelper;


class DocumentsCest
{
    public $sharedData = [
        'documentAreaName' => 'Doc Area',
        'file' => 'd913d35c-915c-41db-a126-613d04694752.txt'        
    ];
    private $documentAreaName = 'Doc Area';
    public $file = 'd913d35c-915c-41db-a126-613d04694752.txt';

    public $companyInfo = [
        'companyId' => 5074,
        'companyName' => 'Test Company 09/2022'
    ];

    public $mainUserAcc = [
        'username' => 'Acc2@testermail.com',
        'password' => 'PASS*w01rd'
    ];

    public $secondUserAcc = [
        'user' => ' Acc3 test company ',
        'username' => 'Acc3@testermail.com',
        'password' => 'PASS*w01rd3'
    ];

    public $consultantUserAcc = [
        'company' => 'Acme AB',
        'user' => 'Acc4 consultant',
        'username' => 'Acc4consultant@tester.com',
        'password' => 'PASS*w01rd4'
    ];

    public $directoryParam = [
        'parentDirectoryId' => 2930954,
        'directoryName' => 'Directory',
        'userId' => '35703',
        'permission' => 'viewer'
    ];

    public $fileParam = [
        'contentType' => "text/plain",
        'name' => 'File',
        'relativePath' => 'API',
        'parentDirectoryId' => 0,  
    ];
   
    public $cookieDefaultParams = [
        'path' => '/',
        'secure' => true,
        'httpOnly' => false,
        'domain' => 'idp-develop-devdb.staging.cozone.com'
    ];

    public function _before(
        AcceptanceTester $I,
        LoginPage $loginPage,
        DocumentsPage $documentsPage, 
        PasswordHelper $passwordHelper,
        \Codeception\Scenario $scenario) : void
    {
        $I->setPageAndCookie(LoginPage::URL);        
        $documentsPage->redirectToDocumentsPage($this->mainUserAcc, $loginPage);
        $I->loginApi($this->mainUserAcc);
        $I->setCookie('automation_testing', 'selenium');       
    }

    public function _after( 
        AcceptanceTester $I,
        DocumentsHelper $documentsHelper, 
        \Codeception\Scenario $scenario) : void
    {
        if($scenario->current('name') === 'tryToCreateDocumentArea')
        {
            $I->waitAndClick(DocumentsPage::SIDEBAR_CURRENT_COMPANY, 60);
        }
    }

    // tests
    public function tryToUploadFile(
        AcceptanceTester $I, 
        LoginPage $loginPage, 
        DocumentsPage $documentsPage,
        DocumentsHelper $documentsHelper,
        PasswordHelper $passwordHelper,
        ) : void
    {
        $documentsPage->createDocumentArea($this->sharedData['documentAreaName']);
        $documentsPage->fileUpload($this->sharedData, $this->companyInfo['companyName']);
        $directoryId = $documentsHelper->getDocumentAreaId($this->companyInfo['companyName'], $this->sharedData['documentAreaName']);
        $fileId = $documentsHelper->getFileId( $directoryId, $this->sharedData['file'], $passwordHelper);
        $documentsPage->fileDelete($this->sharedData['documentAreaName'], $fileId);
        $documentsPage->directoryDelete($this->sharedData['documentAreaName'], $directoryId);
    }


    public function tryToCreateDocumentArea(
        AcceptanceTester $I, 
        LoginPage $loginPage, 
        DocumentsPage $documentsPage, 
        PortalPage $portalPage,
        DocumentsHelper $documentsHelper) : void
    {
        $documentsPage->createDocumentArea($this->sharedData['documentAreaName']);        
        $documentsPage->grantAccessToDirectory($this->sharedData['documentAreaName'], $this->consultantUserAcc, ' Viewer ');
        $directoryId = $documentsHelper->getDocumentAreaId($this->companyInfo['companyId'], $this->sharedData['documentAreaName']);
        $documentsPage->directoryDelete($this->sharedData['documentAreaName'], $directoryId);
    }

    
    public function tryToSetUpStructure(AcceptanceTester $I) : void
    {
        $I->amOnUrl(DocumentsPage::URL);
        $I->waitForElementVisible(DocumentsPage::NAV_SIDEBAR, 60);
        $I->waitAndClick(DocumentsPage::NAV_SETUP_STRUCTURE, 60);
        $I->waitAndClick(DocumentsPage::STRUCTURE_FIELD_COUNTRY, 60);               
        $I->waitVisibleAndClick(DocumentsPage::STRUCTURE_COUTRY_SWEDEN, 60);
        $I->waitAndClick(DocumentsPage::STRUCTURE_FIELD_YEAR, 60);
        $I->waitAndClick(DocumentsPage::STRUCTURE_YEAR_2024, 60);        
        $I->waitAndClick(DocumentsPage::STRUCTURE_FIELD_LANGUAGE, 60);  
        $I->waitAndClick(DocumentsPage::STRUCTURE_LANGUAGE_EN, 60);              
        $I->waitAndClick(DocumentsPage::STRUCTURE_FIELD_DOC_AREAS, 60);
        $I->waitForElementVisible(DocumentsPage::STRUCTURE_DOC_AREAS_HR, 60);
        $I->selectOption(DocumentsPage::STRUCTURE_DOC_AREAS_HR);  
        $I->waitForElementVisible(DocumentsPage::STRUCTURE_DOC_AREAS_HR, 60);      
        $I->selectOption(DocumentsPage::STRUCTURE_DOC_AREAS_FINANCE);
        $I->waitAndClick(DocumentsPage::STRUCTURE_FIELD_COMPANIES, 60); 
        $I->fillField(DocumentsPage::STRUCTURE_COMPANIES_INPUT, $this->companyName);
        $I->waitAndClick(['xpath' => "//label[text()='$this->companyInfo['companyName']']"], 60);        
        $I->waitAndClick(DocumentsPage::STRUCTURE_CREATE, 60);
        $I->waitForElementVisible(DocumentsPage::ALERT_SUCCESS, 60);

        $I->amOnUrl(DocumentsPage::URL);
        $I->waitAndClick(['xpath' => "//a[text()[contains(., '{$this->companyInfo['companyName']}')]]"] ,60);
        $I->waitAndClick(DocumentsPage::DIRECTORY_FINANCE_REPORTS, 60);
        $I->seeElement(DocumentsPage::DIRECTORY_FROM_AZETS);
        $I->seeElement(DocumentsPage::DIRECTORY_TO_AZETS);

        $I->amOnUrl(DocumentsPage::URL);
        $I->waitAndClick(['xpath' => "//a[text()[contains(.,'{$this->companyInfo['companyName']}')]]"], 60);        
        $I->waitAndClick(DocumentsPage::DIRECTORY_HR_REPORTS, 60);
        $I->seeElement(DocumentsPage::DIRECTORY_FROM_AZETS);
        $I->seeElement(DocumentsPage::DIRECTORY_TO_AZETS);
    }



    public function tryToFileUploadApi(AcceptanceTester $I,
        DocumentsHelper $documentsHelper,
        PasswordHelper $passwordHelper, 
        DocumentsPage $documentsPage,
        LoginPage $loginPage) : void
    {       

        $newDirect = $documentsHelper->createNewDirectory($this->directoryParam, $passwordHelper);
        $this->fileParam['parentDirectoryId'] = $newDirect;
        $newFile = $documentsHelper->uploadNewFile($this->fileParam, $this->sharedData['file']);
        $I->reloadPage();

        $documentsPage->grantAccessToDirectory($this->directoryParam['directoryName'],$this->consultantUserAcc['company'], $this->consultantUserAcc['user'] ); 

        $I->amOnUrl(DocumentsPage::URL);
        $I->waitAndClick(['xpath' => "//a[text()[contains(.,'{$this->directoryParam['directoryName']}')]]"], 60);
        $I->waitAndClick(['xpath' => "//a[text()[contains(.,'{$this->fileParam['relativePath']}')]]"], 12);
        $I->waitAndclick(['css' => "div[row-id='{$newFile}'] fds-icon-button[icon='send'] button "]);
        $I->waitAndClick(DocumentsPage::BUTTON_SELECT_APPROVERS);        
        $I->waitForElementVisible(DocumentsPage::REQUEST_SELECT_APPROVERS, 60);
        $I->checkOption(['xpath' => "//fds-selector-menu-checkbox/label[text()[contains(.,'{$this->consultantUserAcc['user']}')]]"]);       
        $I->waitAndClick(DocumentsPage::BUTTON_REQUEST_APPROVAL);

        $consultantUserF = $I->haveFriend('consultantUser');
        $consultantUserF->does(function (AcceptanceTester $I) use ($loginPage, $documentsPage, $newFile){
            $I->amOnUrl(DocumentsPage::URL);
            $I->maximizeWindow();
            $I->resetCookie('OptanonAlertBoxClosed');
            $I->wait(5);           
            $I->reloadPage();              
            $I->setCookie('OptanonAlertBoxClosed', '2022-08-23T11:29:30.562Z', $this->cookieDefaultParams);
            $I->reloadPage();           
            $loginPage->login($this->consultantUserAcc);

            $I->waitForElementVisible('.scrollable doc-sidebar', 60);
            $I->maximizeWindow();
            $I->seeElement('fds-header-burger-menu');

            $I->waitForElementVisible(['xpath' => "//a/div/span[@class='tree-node__label-name'][text()[contains(., '{$this->companyInfo['companyName']}')]]"], 60);
            $I->click(['xpath' => "//a[text()[contains(.,'{$this->companyInfo['companyName']}')]]"]);
            $I->waitAndClick(['xpath' => "//a[text()[contains(.,'{$this->directoryParam['directoryName']}')]]"]);
            $I->waitAndClick(['xpath' => "//a[text()[contains(.,'{$this->fileParam['relativePath']}')]]"], 60);
            $I->waitAndclick(['css' => "div[row-id='{$newFile}'] fds-icon-button[icon='approved-action'] button "], 60);
            $I->waitAndClick(DocumentsPage::BUTTON_APPROVE);
        });

        $I->reloadPage();
        $I->waitAndClick(Locator::contains("div[row-id='{$newFile}'] fds-tag.tag--green > span", " Approved "), 60);
        $I->waitForElementVisible(Locator::contains('div.popover a', $this->consultantUserAcc['username']), 60);
        $I->seeElement(Locator::contains('div.popover a', $this->consultantUserAcc['username']));

        $consultantUserF->does(function (AcceptanceTester $I) use ($loginPage, $documentsPage, $newDirect, $newFile){
            $I->amOnUrl(DocumentsPage::URL);
            $I->maximizeWindow();
            $I->waitForElementVisible(['css' => "div[row-id='{$newFile}'] a[data-gtm-id='file-breadcrumbs-cell-directory']"], 60);
            $I->moveMouseOver(['css' => "div[row-id='{$newFile}'] a[data-gtm-id='file-breadcrumbs-cell-directory']"]);
            $I->wait(10);
            $tooltipLocationInfo = $I->grabTextFrom(DocumentsPage::TOOLTIP_INNER_DETAILED);
            $I->see($tooltipLocationInfo, DocumentsPage::TOOLTIP_INNER);
        });

        $consultantUserF->leave();
        $documentsPage->deleteDirectoryApi($newDirect);
    }



}
