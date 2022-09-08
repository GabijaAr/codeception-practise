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
use Tests\Support\Helper\DocumentHelper;


class DocumentsCest
{
    private $documentAreaName = 'Doc Area';
    public $file = 'd913d35c-915c-41db-a126-613d04694752.txt';
    public $company = 5074;
    public $companyName = 'Test Company 09/2022'; 

    public $mainUserAcc = [
        'username' => 'Acc2@testermail.com',
        'password' => 'PASS*w01rd'
    ];

    public $secondUserAcc = [
        'user' => ' Acc3 test company ',
        'username' => 'Acc3@testermail.com',
        'password' => 'PASS*w01rd3'
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
        DocumentHelper $documentHelper, 
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
        DocumentHelper $documentHelper,
        PasswordHelper $passwordHelper,
        ) : void
    {
        $documentsPage->createDocumentArea($this->documentAreaName);
        $documentsPage->fileUpload($this->file, $this->documentAreaName);
        $directoryId = $documentHelper->getDocumentAreaId($this->company, $this->documentAreaName);
        $fileId = $documentHelper->getFileId( $directoryId, $this->file, $passwordHelper);
        $documentsPage->fileDelete($this->documentAreaName, $fileId);
        $documentsPage->directoryDelete($this->documentAreaName, $directoryId);
    }


    public function tryToCreateDocumentArea(
        AcceptanceTester $I, 
        LoginPage $loginPage, 
        DocumentsPage $documentsPage, 
        PortalPage $portalPage,
        DocumentHelper $documentHelper) : void
    {
        $documentsPage->createDocumentArea($this->documentAreaName);        
        $documentsPage->grantAccessToDirectory($this->documentAreaName, $this->secondUserAcc['user']);

        $directoryId = $documentHelper->getDocumentAreaId($this->company, $this->documentAreaName);
        $documentsPage->directoryDelete($this->documentAreaName, $directoryId);

        
    }

    
    public function tryToSetUpStructure(AcceptanceTester $I) : void
    {
        $I->amOnUrl(DocumentsPage::URL);
        $I->waitForElementVisible(DocumentsPage::DRIVE_NAV_ASIDE, 60);
        $I->waitAndClick(DocumentsPage::NAV_SETUP_STRUCTURE, 60);

        $I->waitAndClick(DocumentsPage::STRUCTURE_FIELD_COUNTRY, 60);               
        $I->waitVisibleAndClick(DocumentsPage::STRUCTURE_COUTRY_SWEDEN, 60);
        // $I->waitVisibleAndClick(Locator::contains('button.selector__item', ' Sweden '), 60);
        
        $I->waitAndClick(DocumentsPage::STRUCTURE_FIELD_YEAR, 60);
        $I->waitAndClick(DocumentsPage::STRUCTURE_YEAR_2024, 60);        
        // $I->waitAndClick(Locator::contains('button.selector__item', ' 2024 '), 60);

        $I->waitAndClick(DocumentsPage::STRUCTURE_FIELD_LANGUAGE, 60);  
        $I->waitAndClick(DocumentsPage::STRUCTURE_LANGUAGE_EN, 60);              
        // $I->waitAndClick(Locator::contains('button.selector__item', ' EN '), 60);

        $I->waitAndClick(DocumentsPage::STRUCTURE_FIELD_DOC_AREAS, 60);
        $I->waitAndClick(DocumentsPage::STRUCTURE_DOC_AREAS_HR, 60);        
        // $I->waitAndClick(Locator::contains('label.custom-control-label', 'HR Reports'), 60);
        $I->waitAndClick(DocumentsPage::STRUCTURE_DOC_AREAS_FINANCE, 60);
        // $I->waitAndClick(Locator::contains('label.custom-control-label', 'Finance Reports'), 60);

        $I->waitAndClick(DocumentsPage::STRUCTURE_FIELD_COMPANIES, 60); 
        $I->fillField(DocumentsPage::STRUCTURE_COMPANIES_INPUT, $this->companyName);
        $I->waitAndClick(['xpath' => "//label[text()=' Test Company 09/2022 ']"], 60);        
        // $I->waitAndClick(Locator::contains('label', ' Test Company 09/2022 '), 60);

        $I->waitAndClick(DocumentsPage::STRUCTURE_CREATE, 60);
        // $I->waitAndClick(Locator::contains('button', ' Create structure '), 60);
        $I->waitForElementVisible(DocumentsPage::ALERT_SUCCESS, 60);

        $I->amOnUrl(DocumentsPage::URL);
        $I->waitAndClick(Locator::contains('fds-tree-item[data-test-id="sidebar-current-company"] a', $this->companyName), 60);
        $I->waitAndClick(Locator::contains('fds-tree-item[icon="document-area"] a', 'Finance Reports'), 60);
        $I->seeElement(Locator::contains('fds-tree-item a', 'From Azets'));
        $I->seeElement(Locator::contains('fds-tree-item a', 'To Azets'));

        $I->amOnUrl(DocumentsPage::URL);
        $I->waitAndClick(Locator::contains('fds-tree-item[data-test-id="sidebar-current-company"] a', $this->companyName), 60);        
        $I->waitAndClick(Locator::contains('fds-tree-item[icon="document-area"] a', 'HR Reports'), 60);
        $I->seeElement(Locator::contains('fds-tree-item a', 'From Azets'));
        $I->seeElement(Locator::contains('fds-tree-item a', 'To Azets'));
    }



    public function tryToFileUploadApi(AcceptanceTester $I,
        DocumentHelper $documentHelper,
        PasswordHelper $passwordHelper, 
        DocumentsPage $documentsPage,
        LoginPage $loginPage) : void
    {       

        $newDirect = $documentHelper->createNewDirectory($this->directoryParam, $passwordHelper);
        $this->fileParam['parentDirectoryId'] = $newDirect;

        $newFile = $documentHelper->uploadNewFile($this->fileParam, $this->file);
        $I->reloadPage();

        $documentsPage->grantAccessToDirectory($this->directoryParam['directoryName'], $this->secondUserAcc['user']); 

        $I->amOnUrl(DocumentsPage::URL);
        $I->waitAndClick(Locator::contains('a', $this->directoryParam['directoryName']), 12);
        $I->waitAndClick(Locator::contains('div.text-truncate a', $this->fileParam['relativePath']), 12);

        $I->waitAndclick(['css' => "div[row-id='{$newFile}'] fds-icon-button[icon='send'] button "]);
        $I->waitAndClick(Locator::contains('button', 'Select approvers'));
        $I->waitForElementVisible(DocumentsPage::REQUEST_SELECT_APPROVERS, 60);
        $I->checkOption(Locator::contains('fds-selector-menu-checkbox > label', ' Acc3 test company '));
        $I->waitAndClick(Locator::contains('button', ' Request for approval '));

        $secondUserF = $I->haveFriend('secondUser');
        $secondUserF->does(function (AcceptanceTester $I) use ($loginPage, $documentsPage, $newFile){
            $I->amOnUrl(DocumentsPage::URL);
            $I->maximizeWindow();
            $I->resetCookie('OptanonAlertBoxClosed');
            $I->wait(5);           
            $I->reloadPage();              
            $I->setCookie('OptanonAlertBoxClosed', '2022-08-23T11:29:30.562Z', $this->cookieDefaultParams);
            $I->reloadPage();           
            $loginPage->login($this->secondUserAcc);

            $I->waitAndClick(Locator::contains('a', $this->directoryParam['directoryName']));
            $I->waitAndClick(Locator::contains('div.text-truncate a', $this->fileParam['relativePath']), 60);
            $I->waitAndclick(['css' => "div[row-id='{$newFile}'] fds-icon-button[icon='approved-action'] button "], 60);
            $I->waitAndClick(Locator::contains('button', ' Approve '));
        });

        $I->reloadPage();
        $I->waitAndClick(Locator::contains("div[row-id='{$newFile}'] fds-tag.tag--green > span", " Approved "), 60);
        $I->waitForElementVisible(Locator::contains('div.popover a', $this->secondUserAcc['username']), 60);
        $I->seeElement(Locator::contains('div.popover a', $this->secondUserAcc['username']));

        $secondUserF->does(function (AcceptanceTester $I) use ($loginPage, $documentsPage, $newDirect, $newFile){
            $I->amOnUrl(DocumentsPage::URL);
            $I->maximizeWindow();
            $I->waitForElementVisible(['css' => "div[row-id='{$newFile}'] a[data-gtm-id='file-breadcrumbs-cell-directory']"], 60);
            $I->moveMouseOver(['css' => "div[row-id='{$newFile}'] a[data-gtm-id='file-breadcrumbs-cell-directory']"]);
            $I->wait(10);
            $tooltipLocationInfo = $I->grabTextFrom(self::TOLLTIP_INNER_DETAILED);
            $I->see($tooltipLocationInfo, self::TOLLTIP_INNER);
        });

        $secondUserF->leave();
        $documentsPage->deleteDirectoryApi($newDirect);
    }



}
