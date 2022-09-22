<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;
use Codeception\Util\Shared\Asserts;
use Codeception\Util\Locator;
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

    private $mainUserAcc = [
        'companyId' => 5074,
        'company' => 'Test Company 09/2022',
        'user' => 'Acc2 test company',                
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
        if($scenario->current('name') === 'tryToUploadFile' || $scenario->current('name') === 'tryToCreateDocumentArea')
            {
                $directoryId = $documentsHelper->getDocumentAreaId($this->mainUserAcc['companyId'], $this->sharedData['documentAreaName']);
                $documentsPage->directoryDelete($this->sharedData['documentAreaName'], $directoryId);
            }
        if($scenario->current('name') === 'tryToSetUpStructure')
            {
                $directoryId = $documentsHelper->getDocumentAreaId($this->mainUserAcc['companyId'], 'Finance Reports');
                $documentsPage->directoryDelete($this->sharedData['documentAreaName'], $directoryId);
                $directoryId = $documentsHelper->getDocumentAreaId($this->mainUserAcc['companyId'], 'HR Reports');
                $documentsPage->directoryDelete($this->sharedData['documentAreaName'], $directoryId);
            }
        if($scenario->current('name') === 'tryToFileUploadApi')
            {
                $documentsPage->deleteDirectoryApi($this->fileParam['parentDirectoryId']);
            }
    }

    // tests
    public function tryToUploadFile(
        AcceptanceTester $I, 
        DocumentsPage $documentsPage,
        DocumentsHelper $documentsHelper,
        PasswordHelper $passwordHelper,
        ) : void
    {
        $documentsPage->createDocumentArea($this->sharedData['documentAreaName']);
        $documentsPage->fileUpload($this->sharedData, $this->mainUserAcc['company']);
        $directoryId = $documentsHelper->getDocumentAreaId($this->mainUserAcc['companyId'], $this->sharedData['documentAreaName']);
        $fileId = $documentsHelper->getFileId( $directoryId, $this->sharedData['file'], $passwordHelper);
        $documentsPage->fileDelete($this->sharedData['documentAreaName'], $fileId);
    }

    public function tryToCreateDocumentArea(
        AcceptanceTester $I, 
        LoginPage $loginPage, 
        DocumentsPage $documentsPage, 
        DocumentsHelper $documentsHelper) : void
    {
        $documentsPage->createDocumentArea($this->sharedData['documentAreaName']);    
        $documentsPage->fileUpload($this->sharedData, $this->mainUserAcc['company']);
        $documentsPage->grantAccessToDirectory(
            $this->sharedData['documentAreaName'],
            $this->consultantUserAcc, 
            $this->mainUserAcc['company'], 
            $this->accessPermissions['permissionViewer']);
       
        $consultantUser = $I->haveFriend('consultantUser');
        $consultantUser->does(function (AcceptanceTester $I) use ($loginPage, $documentsPage, $documentsHelper){
            $I->setPageAndCookieForFriend(DocumentsPage::URL, $this->consultantUserAcc, $loginPage);
            $I->waitAndClick($documentsPage->getDirectoryShareWMe($this->directoryParam['parentDirectoryId']));
            $I->waitAndClick($I->getLinkContains($this->sharedData['documentAreaName']));

            $I->amGoingTo('check access permissions as viewer');  
            $I->waitAndClick(DocumentsPage::BUTTON_FILE_DOWNLOAD);
            $I->dontSeeElement(DocumentsPage::DIRECTORY_MORE_BUTTON);
            $I->dontSeeElement(DocumentsPage::BUTTON_FILE_SEND_REQUEST);
            $I->waitAndClick(DocumentsPage::FILE_FRIEND_MORE_ACTIONS);
            $I->dontSeeElement($I->getButtonContains('Move file to...'));
            $I->dontSeeElement($I->getButtonContains('Rename'));            
            $I->dontSeeElement($I->getButtonContains('Delete'));            
            $I->dontSeeElement($I->getButtonContains('View activity log'));            
            $I->dontSeeElement($I->getButtonContains('Set duration time'));             
        });
        $consultantUser->leave();
    }

    
    public function tryToSetUpStructure(AcceptanceTester $I, DocumentsPage $documentsPage) : void
    {
        $I->amGoingTo('set up structure with correct data'); 
        $I->amOnUrl(DocumentsPage::URL);
        $I->waitAndClick(DocumentsPage::NAV_SETUP_STRUCTURE);
        $I->waitAndClick(DocumentsPage::STRUCTURE_FIELD_COUNTRY);               
        $I->waitVisibleAndClick($I->getButtonContains('Sweden'));
        $I->waitAndClick(DocumentsPage::STRUCTURE_FIELD_YEAR);
        $I->waitAndClick($I->getButtonContains('2024'));        
        $I->waitAndClick(DocumentsPage::STRUCTURE_FIELD_LANGUAGE);  
        $I->waitAndClick($I->getButtonContains('EN'));              
        $I->waitAndClick(DocumentsPage::STRUCTURE_FIELD_DOC_AREAS);
        $I->waitForElementVisible(DocumentsPage::STRUCTURE_DOC_AREAS_HR, 60);
        $I->checkOption(DocumentsPage::STRUCTURE_DOC_AREAS_HR);  
        $I->waitForElementVisible(DocumentsPage::STRUCTURE_DOC_AREAS_HR, 60);      
        $I->checkOption(DocumentsPage::STRUCTURE_DOC_AREAS_FINANCE);
        $I->waitAndClick(DocumentsPage::STRUCTURE_FIELD_COMPANIES); 
        $I->fillField(DocumentsPage::STRUCTURE_COMPANIES_INPUT, $this->mainUserAcc['company']);
        $I->waitAndClick($I->getLabelContains($this->mainUserAcc['company']));           

        $I->waitAndClick($I->getButtonContains('Create structure'));
        $I->waitForElementVisible(DocumentsPage::ALERT_SUCCESS, 60);
        
        $I->amGoingTo('check generated folders structure');
        $I->amOnUrl(DocumentsPage::URL);
        $I->waitAndClick(DocumentsPage::DIRECTORY_FINANCE_REPORTS);
        $I->waitAndClick($documentsPage->getDirectoryAside($this->mainUserAcc['company']));
        $I->waitForElementVisible(DocumentsPage::DIRECTORY_FROM_AZETS, 60);
        $I->waitForElementVisible(DocumentsPage::DIRECTORY_TO_AZETS, 60);
        $I->amOnUrl(DocumentsPage::URL);
        $I->waitAndClick($documentsPage->getDirectoryAside($this->mainUserAcc['company']));        
        $I->waitAndClick(DocumentsPage::DIRECTORY_HR_REPORTS);
        $I->waitForElementVisible(DocumentsPage::DIRECTORY_FROM_AZETS, 60);
        $I->waitForElementVisible(DocumentsPage::DIRECTORY_TO_AZETS, 60);
    }

    public function tryToFileUploadApi(AcceptanceTester $I,
        DocumentsHelper $documentsHelper,
        PasswordHelper $passwordHelper, 
        DocumentsPage $documentsPage,
        LoginPage $loginPage) : void
    {       
        $I->amGoingTo('create directory with file through api'); 
        $this->fileParam['parentDirectoryId'] = $documentsHelper->createNewDirectory($this->directoryParam, $passwordHelper);
        $newFile = $documentsHelper->uploadNewFile($this->fileParam, $this->sharedData['file']);
        $I->reloadPage();

        $documentsPage->grantAccessToDirectory(
            $this->directoryParam['directoryName'], 
            $this->consultantUserAcc, $this->mainUserAcc['company'], 
            $this->accessPermissions['permissionViewer'] ); 

        $I->amGoingTo('request file approval from consultant account');        
        $I->amOnUrl(DocumentsPage::URL);
        $I->waitAndClick($I->getSpanContains($this->mainUserAcc['company']));         
        $I->waitAndClick($I->getSpanContains($this->directoryParam['directoryName']));       
        $I->waitAndClick($documentsPage->getRelativePath($this->fileParam['relativePath']));
        $I->waitAndclick($documentsPage->getButtonSend($newFile));
        $I->waitAndClick(DocumentsPage::BUTTON_SELECT_APPROVERS);        
        $I->waitForElementVisible(DocumentsPage::REQUEST_SELECT_APPROVERS, 60);
        $I->checkOption($documentsPage->getCheckboxUser($this->consultantUserAcc['user']));
        $I->waitAndClick(DocumentsPage::BUTTON_REQUEST_APPROVAL);

        $consultantUserF = $I->haveFriend('consultantUser');
        $consultantUserF->does(function (AcceptanceTester $I) use ($loginPage, $documentsPage, $newFile){
            $I->setPageAndCookieForFriend(DocumentsPage::URL, $this->consultantUserAcc, $loginPage);

            $I->amGoingTo('approve file request from company account'); 
            $I->waitAndClick($documentsPage->getDirectoryShareWMe($this->directoryParam['parentDirectoryId']));
            $I->waitAndClick($I->getLinkContains($this->directoryParam['directoryName']));
            $I->waitAndClick($I->getLinkContains($this->fileParam['relativePath']));
            $I->waitAndclick($documentsPage->getButtonWFunction($newFile, 'approved-action'));
            $I->waitAndClick(DocumentsPage::BUTTON_APPROVE);
        });

        $I->reloadPage();
        $I->waitAndClick(['xpath' => "//div[@row-id='{$newFile}']//fds-tag[@class='tag tag--clickable tag--green']/span[text()[contains(.,'Approved')]]"], 60);        
        $I->waitForElementVisible(Locator::contains('div.popover a', $this->consultantUserAcc['username']), 60);
        $I->seeElement(Locator::contains('div.popover a', $this->consultantUserAcc['username']));

        $consultantUserF->does(function (AcceptanceTester $I) use ($newFile)
        {
            $I->reloadPage();
            $I->waitAndClick(Locator::contains("div[row-id='{$newFile}'] fds-tag.tag--green > span", " Approved "));
            $I->waitForElementVisible(Locator::contains('div.popover a', $this->consultantUserAcc['username']), 60);
            $I->seeElement(Locator::contains('div.popover a', $this->consultantUserAcc['username']));

            $I->amOnUrl(DocumentsPage::URL_FILE_DROP);           
            $I->waitAndClick(DocumentsPage::CONSULT_FILE_UPLOAD_BUTTON);        
            $I->attachFile(DocumentsPage::FILE_ATTACH, "{$this->sharedData['file']}");

            $I->amOnUrl(DocumentsPage::URL); 
            $I->waitAndClick(DocumentsPage::RECENT_FROM_CONSULTANT); 
            $I->waitAndClick(DocumentsPage::RECENT_TO_CONSULTANT);        

            $I->amGoingTo('check path indicated in tooltip');            
            $I->waitForElementVisible(DocumentsPage::BREADCRUMBS_PATH, 60);
            $I->moveMouseOver(DocumentsPage::BREADCRUMBS_PATH);
            $tooltipLocationInfo = $I->grabTextFrom(DocumentsPage::TOOLTIP_INNER);
            $I->see($tooltipLocationInfo, DocumentsPage::TOOLTIP_INNER);
        });

        $consultantUserF->leave();
    }

}
