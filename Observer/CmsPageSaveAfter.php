<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Registry;
use PluginCompany\CmsRevisions\Model\PageRepository;
use PluginCompany\CmsRevisions\Model\PageFactory;

class CmsPageSaveAfter implements ObserverInterface
{
    private $pageRevisionFactory;
    private $pageRevisionRepository ;
    private $observer;
    private $page;
    private $pageRevision;
    private $adminSession;
    /**
     * @var Registry
     */
    private $registry;

    public function __construct(
        PageFactory $pageRevisionFactory,
        PageRepository $pageRevisionRepository,
        Session $adminSession,
        Registry $registry
    ) {
        $this->pageRevisionFactory = $pageRevisionFactory;
        $this->pageRevisionRepository = $pageRevisionRepository;
        $this->adminSession = $adminSession;
        $this->registry = $registry;
    }
    
    public function execute(Observer $observer)
    {
        if($this->isRevisionAlreadyCreated()) {
            return;
        }
        $this->observer = $observer;
        $this->createPageRevisionBasedOnPageData();
        $this->registerRevisionCreated();
    }

    private function isRevisionAlreadyCreated()
    {
        return $this->registry->registry('revision_created');
    }

    private function registerRevisionCreated()
    {
        $this->registry->register('revision_created', true);
        return $this;
    }
    
    private function createPageRevisionBasedOnPageData()
    {
        $this
            ->initCmsPage()
            ->initRevisionBasedOnCmsPage()
            ->addAdminDetailsToRevision()
            ->saveRevisionAsCurrent()
        ;
        return $this;
    }
    
    private function initCmsPage()
    {
        $this->page = $this->getPageFromObserver();
        return $this;
    }
    
    private function getPageFromObserver()
    {
        return $this->observer
            ->getEvent()
            ->getDataObject();
    }
    
    private function initRevisionBasedOnCmsPage()
    {
        $this->pageRevision = $this->createRevisionBasedOnCmsPage();
        return $this;
    }
    
    private function createRevisionBasedOnCmsPage()
    {
        return $this->pageRevisionFactory
            ->createFromCmsPage($this->page);
    }
    
    private function addAdminDetailsToRevision()
    {
        $this->pageRevision
            ->setAdminUserId($this->getCurrentAdminUserId());
        return $this;
    }
    
    private function getCurrentAdminUserId()
    {
        return $this->adminSession->getUser()->getId();
    }
    
    private function saveRevisionAsCurrent()
    {
        $this->pageRevisionRepository
            ->saveAsCurrent($this->pageRevision);
    }
}
