<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;
use Tests\Support\Page\Acceptance\DocumentsPage;
use Tests\Support\Page\Acceptance\LoginPage;
use Tests\Support\Page\Acceptance\PortalPage;
use Codeception\Util\Locator;

class DocumentsCest
{
    private $username = 'Acc2@testermail.com';
    private $password = 'PASS*w01rd';
    private $secondUsername = 'Acc3@testermail.com';
    private $secondPassword = 'PASS*w01rd3';

    private $documentAreaName = 'Test10';


    public function _before(AcceptanceTester $I, LoginPage $loginPage, DocumentsPage $documentsPage) : void
    {
        $I->setPageAndCookie(LoginPage::URL);        
        $documentsPage->redirectToDocumentsPage($this->username, $this->password, $loginPage);
    }

    // tests
    public function TryToUploadFile(AcceptanceTester $I, LoginPage $loginPage, DocumentsPage $documentsPage) : void
    {
        $documentsPage->fileUpload('d913d35c-915c-41db-a126-613d04694752.txt');
        $documentsPage->fileDelete();
    }

    public function TryToCreateDocumentArea(AcceptanceTester $I, LoginPage $loginPage, PortalPage $portalPage) : void
    {
        // create
        $I->amOnUrl(DocumentsPage::URL);
        $I->waitForElementVisible(DocumentsPage::DRIVE_NAV_ASIDE, 120);
        $I->waitAndClick(DocumentsPage::NEW_DOCUMENT_AREA_BUTTON);
        $I->waitAndFill(DocumentsPage::DOCUMENT_AREA_NAME_INPUT, "{$this->documentAreaName}");
        $I->waitAndClick(DocumentsPage::PRIMARY_BUTTON);
        
        // gran acess to doc area
        $I->amOnUrl(DocumentsPage::URL); 
        $I->waitAndClick(Locator::contains('fds-tree-item[icon="document-area"] a', "{$this->documentAreaName}"));
        $I->waitAndClick('#users-tab');
        $I->waitAndClick(Locator::contains('fds-button button.btn-text-flush', ' Add users '));        
        $I->waitAndClick(Locator::contains('button', 'Select users'));
        $I->waitAndClick(Locator::contains('fds-selector-menu-checkbox label', ' Acc3 test company '));
        $I->waitAndClick(Locator::contains('fds-button > button.btn-primary', ' Add users '));
        $I->waitAndClick(Locator::contains('fds-selector > button', 'Viewer'));
        $I->click(Locator::contains('fds-card-footer button', 'Save'));
        $I->waitForElementNotVisible('.alert-success', 60);

        // check acess
        // $I->waitAndClick(['css' => 'fds-header-user button.user.nav-link']);
        // $I->click(Locator::contains('fds-header-user-nav-item', ' Logout '));
        // $I->waitForElementVisible(LoginPage::LOGIN_FORM, 60);
        // $I->seeCurrentUrlEquals(LoginPage::URL); 

        // delete hook
        // $I->amOnUrl(DocumentsPage::URL);
        // $I->waitAndClick('fds-tree-item[data-test-id="sidebar-current-company"]');


    }

    public function TryToSetUpStructure(AcceptanceTester $I) : void
    {
        // $I->amOnUrl(DocumentsPage::URL);
        // $I->waitForElementVisible(DocumentsPage::DRIVE_NAV_ASIDE, 120);

    }



}
