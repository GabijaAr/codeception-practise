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
    private $sharedData = [
        'documentAreaName' => 'Doc Area',
        'file' => 'd913d35c-915c-41db-a126-613d04694752.txt'        
    ];

    private $accessPermissions = [
        'permissionViewer' => 'Viewer',
        'permissionEditor' => 'Editor',
        'permissionManager' => 'Manager',        
        'permissionOwner' => 'Owner',
    ];

    private $companyInfo = [
        'companyId' => 5074,
        'companyName' => 'Test Company 09/2022'
    ];

    private $mainUserAcc = [
        'username' => 'Acc2@testermail.com',
        'password' => 'PASS*w01rd'
    ];

    private $consultantUserAcc = [
        'company' => 'Acme AB',
        'user' => 'Acc4 Consultant',
        'username' => 'Acc4consultant@tester.com',
        'password' => 'PASS*w01rd4'
    ];

    private $directoryParam = [
        'parentDirectoryId' => 2930954,
        'directoryName' => 'Directory',
        'userId' => '35703',
        'permission' => 'viewer'
    ];

    private $fileParam = [
        'contentType' => "text/plain",
        'name' => 'File',
        'relativePath' => 'API',
        'parentDirectoryId' => 0,  
    ];
   
    private $cookieDefaultParams = [
        'path' => '/',
        'secure' => true,
        'httpOnly' => false,
        'domain' => 'idp-develop-devdb.staging.cozone.com'
    ];

    public $newDirect = 0;

    public function _before(
        AcceptanceTester $I,
        LoginPage $loginPage,
        DocumentsPage $documentsPage, 
        PasswordHelper $passwordHelper,
        \Codeception\Scenario $scenario) : void
    {
        $I->setPageAndCookie(LoginPage::URL);        
        $I->loginApi($this->mainUserAcc);
        $I->redirectToPage($this->mainUserAcc, DocumentsPage::URL, $loginPage);
        $I->setCookie('automation_testing', 'selenium');      
    }

    public function _after( 
        AcceptanceTester $I,
        DocumentsHelper $documentsHelper,
        DocumentsPage $documentsPage,
        \Codeception\Scenario $scenario) : void
    {
        if($scenario->current('name') === 'tryToCreateDocumentArea')
            {
                $directoryId = $documentsHelper->getDocumentAreaId($this->companyInfo['companyId'], $this->sharedData['documentAreaName']);
                $documentsPage->directoryDelete($this->sharedData['documentAreaName'], $directoryId);
            }
        if($scenario->current('name') === 'tryToFileUploadApi')
            {
                $documentsPage->deleteDirectoryApi($this->newDirect);
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
        $directoryId = $documentsHelper->getDocumentAreaId($this->companyInfo['companyId'], $this->sharedData['documentAreaName']);
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
        $documentsPage->fileUpload($this->sharedData, $this->companyInfo['companyName']);
        $documentsPage->grantAccessToDirectory($this->sharedData['documentAreaName'], $this->consultantUserAcc, $this->companyInfo['companyName'], $this->accessPermissions['permissionViewer']);
       
        $consultantUser = $I->haveFriend('consultantUser');
        $consultantUser->does(function (AcceptanceTester $I) use ($loginPage, $documentsPage, $documentsHelper){
            $I->setPageAndCookieForFriend(DocumentsPage::URL, $this->consultantUserAcc, $loginPage);
            $I->waitAndClick($documentsPage->getDirectoryShareWMe($this->directoryParam['parentDirectoryId']), 60);
            $I->waitAndClick($documentsPage->getLinkDocumentName($this->sharedData['documentAreaName']), 60);

            $I->amGoingTo('check access permissions as viewer');  
            $I->waitAndClick(DocumentsPage::BUTTON_FILE_DOWNLOAD, 60);
            $I->dontSeeElement(DocumentsPage::DIRECTORY_MORE_BUTTON);
            $I->dontSeeElement(DocumentsPage::BUTTON_FILE_SEND_REQUEST);
            $I->waitAndClick(DocumentsPage::FILE_FRIEND_MORE_ACTIONS, 60);
            $I->dontSeeElement($documentsPage->getButtonContains('Move file to...'));
            $I->dontSeeElement($documentsPage->getButtonContains('Rename'));            
            $I->dontSeeElement($documentsPage->getButtonContains('Delete'));            
            $I->dontSeeElement($documentsPage->getButtonContains('View activity log'));            
            $I->dontSeeElement($documentsPage->getButtonContains('Set duration time'));             
        });

        $consultantUser->leave();
    }

    
    public function tryToSetUpStructure(AcceptanceTester $I) : void
    {
        $I->amGoingTo('set up structure with correct data'); 
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
        $I->waitAndClick($documentsPage->getLabelContains($this->companyInfo['companyName']), 60);           
            
        $I->waitAndClick(DocumentsPage::STRUCTURE_CREATE, 60);
        $I->waitForElementVisible(DocumentsPage::ALERT_SUCCESS, 60);

        $I->amGoingTo('check generated folders structure');
        $I->amOnUrl(DocumentsPage::URL);
        $I->waitAndClick($documentsPage->getLinkContains($this->companyInfo['companyName']), 60);
        
        $I->waitAndClick(DocumentsPage::DIRECTORY_FINANCE_REPORTS, 60);
        $I->seeElement(DocumentsPage::DIRECTORY_FROM_AZETS);
        $I->seeElement(DocumentsPage::DIRECTORY_TO_AZETS);
        $I->amOnUrl(DocumentsPage::URL);
        $I->waitAndClick($documentsPage->getLinkContains($this->companyInfo['companyName']), 60);        
        $I->waitAndClick(DocumentsPage::DIRECTORY_HR_REPORTS, 60);
        $I->seeElement(DocumentsPage::DIRECTORY_FROM_AZETS);
        $I->seeElement(DocumentsPage::DIRECTORY_TO_AZETS);
    }

    public function tryToUploadFileThroughApi(AcceptanceTester $I,
        DocumentsHelper $documentsHelper,
        PasswordHelper $passwordHelper, 
        DocumentsPage $documentsPage,
        LoginPage $loginPage) : void
    {       
        $I->amGoingTo('create directory with file through api'); 
        $this->newDirect = $documentsHelper->createNewDirectory($this->directoryParam, $passwordHelper);
        $this->fileParam['parentDirectoryId'] = $this->newDirect;
        $newFile = $documentsHelper->uploadNewFile($this->fileParam, $this->sharedData['file']);
        $I->reloadPage();

        $documentsPage->grantAccessToDirectory($this->directoryParam['directoryName'], $this->consultantUserAcc, $this->companyInfo['companyName'], $this->accessPermissions['permissionViewer'] ); 

        $I->amGoingTo('request file approval from consultant account');        
        $I->amOnUrl(DocumentsPage::URL);
        $I->waitAndClick($documentsPage->getSpanContains($this->companyInfo['companyName']), 60);         
        $I->waitAndClick($documentsPage->getSpanContains($this->directoryParam['directoryName']), 60);       
        $I->waitAndClick($documentsPage->getRelativePath($this->fileParam['relativePath']), 60);
        $I->waitAndclick($documentsPage->getButtonSend($newFile));
        $I->waitAndClick(DocumentsPage::BUTTON_SELECT_APPROVERS);        
        $I->waitForElementVisible(DocumentsPage::REQUEST_SELECT_APPROVERS, 60);
        $I->checkOption($documentsPage->getCheckboxUser($this->consultantUserAcc['user']));
        $I->waitAndClick(DocumentsPage::BUTTON_REQUEST_APPROVAL);

        $consultantUserF = $I->haveFriend('consultantUser');
        $consultantUserF->does(function (AcceptanceTester $I) use ($loginPage, $documentsPage, $newFile){
            $I->setPageAndCookieForFriend(DocumentsPage::URL, $this->consultantUserAcc, $loginPage);

            $I->amGoingTo('approve file request from company account'); 
            $I->waitForElementVisible(DocumentsPage::DOC_SIDEBAR, 60);

            $I->waitAndClick($documentsPage->getDirectoryShareWMe($this->directoryParam['parentDirectoryId']), 60);
            
            $I->waitAndClick($documentsPage->getLinkContains($this->directoryParam['directoryName']), 60);
            $I->waitAndClick($documentsPage->getLinkContains($this->fileParam['relativePath']), 60);
            
            $I->waitAndclick($documentsPage->getButtonWFunction($newFile, 'approved-action'), 60);
            $I->waitAndClick(DocumentsPage::BUTTON_APPROVE);
        });

        $I->reloadPage();
        $I->waitAndClick(['xpath' => "//div[@row-id='{$newFile}']//fds-tag[@class='tag tag--clickable tag--green']/span[text()[contains(.,'Approved')]]"], 60);        // $I->waitForElementVisible(['xpath' => "//div.popover//a[text()[contains(.,'{$this->consultantUserAcc['username']}')]]"], 60);
        $I->waitForElementVisible(Locator::contains('div.popover a', $this->consultantUserAcc['username']), 60);
        $I->seeElement(Locator::contains('div.popover a', $this->consultantUserAcc['username']));

        $consultantUserF->does(function (AcceptanceTester $I) use ($newFile)
        {
            $I->reloadPage();
            $I->waitAndClick(Locator::contains("div[row-id='{$newFile}'] fds-tag.tag--green > span", " Approved "), 60);
            $I->waitForElementVisible(Locator::contains('div.popover a', $this->consultantUserAcc['username']), 60);
            $I->seeElement(Locator::contains('div.popover a', $this->consultantUserAcc['username']));

            $I->amOnUrl(DocumentsPage::URL_FILE_DROP);           
            $I->waitAndClick(DocumentsPage::BUTTON_UPLOAD_FILE, 60);        
            $I->attachFile(DocumentsPage::FILE_ATTACH, "{$this->sharedData['file']}");

            $I->amOnUrl(DocumentsPage::URL); 
            $I->waitAndClick(DocumentsPage::RECENT_FROM_CONSULTANT, 60); 
            $I->waitAndClick(DocumentsPage::RECENT_TO_CONSULTANT, 60);        

            $I->amGoingTo('check path indicated in tooltip');            
            $I->waitForElementVisible(DocumentsPage::BREADCRUMBS_PATH, 60);
            $I->moveMouseOver(DocumentsPage::BREADCRUMBS_PATH);
            $I->wait(5);
            $tooltipLocationInfo = $I->grabTextFrom(DocumentsPage::TOOLTIP_INNER_DETAILED);
            $I->see($tooltipLocationInfo, DocumentsPage::TOOLTIP_INNER);
        });

        $consultantUserF->leave();
    }



}
