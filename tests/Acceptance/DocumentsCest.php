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
    private $username = 'Acc2@testermail.com';
    private $password = 'PASS*w01rd';
    private $secondUser= ' Acc3 test company ';
    private $secondUsername = 'Acc3@testermail.com';
    private $secondPassword = 'PASS*w01rd3';
    
    private $documentAreaName = 'test api77';

    private $parentDirectoryId = 2930954;
    private $directoryName = 'Directory';
    private $userId = '35703'; 
    private $permission = 'viewer';

    public $parametersFile = [
        'contentType' => "text/plain",
        'name' => 'File',
        'relativePath' => 'API',
        'parentDirectoryId' => 0,  
    ];

    public $file = 'd913d35c-915c-41db-a126-613d04694752.txt';
    public $apiDirectory = '';

    public function _before(
        AcceptanceTester $I,
        LoginPage $loginPage,
        DocumentsPage $documentsPage, 
        PasswordHelper $passwordHelper,
        \Codeception\Scenario $scenario) : void
    {
        $I->setPageAndCookie(LoginPage::URL);        
        $documentsPage->redirectToDocumentsPage($this->username, $this->password, $loginPage);

        if($scenario->current('name') === 'tryToFileUploadApi')
        {
            $I->loginApi($this->username, $this->password);
        }
    }

    public function _after( 
        AcceptanceTester $I,
        DocumentHelper $documentHelper, 
        \Codeception\Scenario $scenario) : void
    {
        if($scenario->current('name') === 'tryToCreateDocumentArea')
        {
            $I->waitAndClick(['css' => 'fds-tree-item[data-test-id="sidebar-current-company"] a']);
            // delete Data Area via id
        }

    }

    // tests
    public function tryToUploadFile(
        AcceptanceTester $I, 
        LoginPage $loginPage, 
        DocumentsPage $documentsPage) : void
    {
        $documentsPage->fileUpload('d913d35c-915c-41db-a126-613d04694752.txt');
        $documentsPage->fileDelete();
    }

    public function tryToCreateDocumentArea(
        AcceptanceTester $I, 
        LoginPage $loginPage, 
        DocumentsPage $documentsPage, 
        PortalPage $portalPage) : void
    {
        $I->amOnUrl(DocumentsPage::URL);
        $I->waitForElementVisible(DocumentsPage::DRIVE_NAV_ASIDE, 120);
        $I->waitAndClick(DocumentsPage::NEW_DOCUMENT_AREA_BUTTON);
        $I->waitAndFill(DocumentsPage::DOCUMENT_AREA_NAME_INPUT, "{$this->documentAreaName}");
        $I->waitAndClick(DocumentsPage::PRIMARY_BUTTON);
        
        $documentsPage->grantAccessToDirectory($this->documentAreaName);

        // // check acess
        // $I ->amOnUrl('https://portal-develop-devdb.staging.cozone.com/ui/#/');
        // // $I->seeElement('button._hj-kWRoL__styles__openStateToggle');
        // $loginPage->logout($portalPage);

        // // $I->waitAndClick(['css' => 'fds-header-user button.user.nav-link']);
        // // $I->click(Locator::contains('fds-header-user-nav-item', ' Logout '));

        // $I->waitForElementVisible(LoginPage::LOGIN_FORM, 60);
        // $I->seeCurrentUrlEquals(LoginPage::URL); 
    }

    public function tryToSetUpStructure(AcceptanceTester $I) : void
    {
        $I->amOnUrl(DocumentsPage::URL);
        $I->waitForElementVisible(DocumentsPage::DRIVE_NAV_ASIDE, 60);
        $I->waitAndClick(['css' => 'a[href="/ui/default-structure-setup"]'], 60);

        $I->waitAndClick('fds-selector-field[formcontrolname="country"]', 60);
        $I->waitForElementVisible(Locator::contains('button.selector__item', ' Sweden '), 60);
        $I->click(Locator::contains('button.selector__item', ' Sweden '));
        
        $I->waitAndClick('fds-selector-field[formcontrolname="year"]', 60);
        $I->waitAndClick(Locator::contains('button.selector__item', ' 2024 '), 60);

        $I->waitAndClick('fds-selector-field[formcontrolname="language"]', 60);        
        $I->waitAndClick(Locator::contains('button.selector__item', ' EN '), 60);

        $I->waitAndClick('fds-selector-field[formcontrolname="documentAreas"]', 60);        
        $I->waitAndClick(Locator::contains('label.custom-control-label', 'HR Reports'), 60);
        $I->waitAndClick(Locator::contains('label.custom-control-label', 'Finance Reports'));

        $I->waitAndClick('fds-selector-field[formcontrolname="companies"]', 60); 
        $I->fillField(['css' => 'fds-selector-field[formcontrolname="companies"] input'], 'Test Company 09/2022');
        $I->waitAndClick(Locator::contains('label', ' Test Company 09/2022 '), 60);

        $I->waitAndClick(Locator::contains('button', ' Create structure '), 60);
        $I->waitForElementVisible('div.alert-success', 60);

        $I->amOnUrl('https://documents-develop-devdb.staging.cozone.com/ui/recent');
        $I->waitAndClick(Locator::contains('fds-tree-item[data-test-id="sidebar-current-company"] a', 'Test Company 09/2022'), 60);
        $I->waitAndClick(Locator::contains('fds-tree-item[icon="document-area"] a', 'Finance Reports'), 60);
        $I->seeElement(Locator::contains('fds-tree-item a', 'From Azets'));
        $I->seeElement(Locator::contains('fds-tree-item a', 'To Azets'));

        $I->amOnUrl('https://documents-develop-devdb.staging.cozone.com/ui/recent');
        $I->waitAndClick(Locator::contains('fds-tree-item[data-test-id="sidebar-current-company"] a', 'Test Company 09/2022'), 60);        
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

        $newDirect = $documentHelper->createNewDirectory(
            $this->parentDirectoryId, 
            $this->directoryName , 
            $this->userId, 
            $this->permission, 
            $passwordHelper);
        $this->parametersFile['parentDirectoryId'] = $newDirect;

        $newFile = $documentHelper->uploadNewFile($this->parametersFile, $this->file);
        $I->reloadPage();

        $documentsPage->grantAccessToDirectory($this->directoryName, $this->secondUser); 
        // // // multisession testing
        $I->amOnUrl('https://documents-develop-devdb.staging.cozone.com/ui/recent');
        $I->waitAndClick(Locator::contains('a', $this->directoryName), 12);
        $I->waitAndClick(Locator::contains('div.text-truncate a', $this->parametersFile['relativePath']), 12);

        $I->waitAndclick(['css' => "div[row-id='{$newFile}'] fds-icon-button[icon='send'] button "]);
        $I->waitAndClick(Locator::contains('button', 'Select approvers'));
        $I->waitForElementVisible('fds-selector-menu-checkbox > label', 60);
        $I->checkOption(Locator::contains('fds-selector-menu-checkbox > label', ' Acc3 test company '));
        $I->waitAndClick(Locator::contains('button', ' Request for approval '));

        $secondUser = $I->haveFriend('secondUser');
        $secondUser->does(function (AcceptanceTester $I) use ($loginPage, $documentsPage, $newFile){
            $I->amOnUrl('https://documents-develop-devdb.staging.cozone.com/ui/recent');
            $I->maximizeWindow();
            $I->resetCookie('OptanonAlertBoxClosed');
            $I->wait(5);
            $I->reloadPage();             
            $I->setCookie('OptanonAlertBoxClosed', '2022-08-23T11:29:30.562Z');
            $I->wait(10);
            $I->reloadPage();           
            $I->amOnUrl('https://documents-develop-devdb.staging.cozone.com/ui/recent');
            $loginPage->login($this->secondUsername, $this->secondPassword );

            $I->waitAndClick(Locator::contains('a', "{$this->directoryName}"));
            $I->waitAndClick(Locator::contains('div.text-truncate a', $this->parametersFile['relativePath']), 12);
            $I->waitAndclick(['css' => "div[row-id='{$newFile}'] fds-icon-button[icon='approved-action'] button "]);
            $I->waitAndClick(Locator::contains('button', ' Approve '));
        });

        $I->reloadPage();
        $I->waitAndClick(Locator::contains("div[row-id='{$newFile}'] fds-tag.tag--green > span", " Approved "));
        $I->waitForElementVisible(Locator::contains('div.popover a', "{$this->secondUsername}"), 60);
        $I->seeElement(Locator::contains('div.popover a', "{$this->secondUsername}"));

        $secondUser->leave();

        $documentsPage->documentAreaDelete($newDirect);
    }



}
