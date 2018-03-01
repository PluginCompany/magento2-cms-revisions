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
use PluginCompany\CmsRevisions\Model\BlockRepository;
use PluginCompany\CmsRevisions\Model\BlockFactory;

class CmsBlockSaveAfter implements ObserverInterface
{
    private $blockRevisionFactory;
    private $blockRevisionRepository ;
    private $observer;
    private $block;
    private $blockRevision;
    private $adminSession;
    /**
     * @var Registry
     */
    private $registry;

    public function __construct(
        BlockFactory $blockRevisionFactory,
        BlockRepository $blockRevisionRepository,
        Session $adminSession,
        Registry $registry
    ) {
    
        $this->blockRevisionFactory = $blockRevisionFactory;
        $this->blockRevisionRepository = $blockRevisionRepository;
        $this->adminSession = $adminSession;
        $this->registry = $registry;
    }
    
    public function execute(Observer $observer)
    {
        if($this->isRevisionAlreadyCreated()) {
            return;
        }
        $this->observer = $observer;
        $this->createRevisionBasedOnBlockData();
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


    private function createRevisionBasedOnBlockData()
    {
        $this
            ->initCmsBlock()
            ->initRevisionBasedOnCmsBlock()
            ->addAdminUserDetailsToRevision()
            ->saveRevisionAsCurrent()
        ;
        return $this;
    }
    
    private function initCmsBlock()
    {
        $this->block = $this->getBlockFromObserver();
        return $this;
    }
    
    private function getBlockFromObserver()
    {
        return $this->observer
            ->getEvent()
            ->getDataObject();
    }
    
    private function initRevisionBasedOnCmsBlock()
    {
        $this->blockRevision = $this->createRevisionBasedOnCmsBlock();
        return $this;
    }

    private function createRevisionBasedOnCmsBlock()
    {
        return $this->blockRevisionFactory
            ->createFromCmsBlock($this->block);
    }

    private function addAdminUserDetailsToRevision()
    {
        $this->blockRevision
            ->setAdminUserId($this->getCurrentAdminUserId());
        return $this;
    }
    
    private function getCurrentAdminUserId()
    {
        if($this->getCurrentAdminUser() && $this->getCurrentAdminUser()->getId()){
            return $this->getCurrentAdminUser()->getId();
        }
        return 5000;
    }

    private function getCurrentAdminUser()
    {
        return $this->adminSession->getUser();
    }
    
    private function saveRevisionAsCurrent()
    {
        $this->blockRevisionRepository
            ->saveAsCurrent($this->blockRevision);
    }
}
