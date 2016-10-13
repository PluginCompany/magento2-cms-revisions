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
use PluginCompany\CmsRevisions\Model\BlockRepository;
use PluginCompany\CmsRevisions\Model\BlockFactory;

class CmsBlockSaveBefore implements ObserverInterface
{
    private $blockRevisionFactory;
    private $blockRevisionRepository ;
    private $observer;
    private $block;
    private $blockRevision;
    private $adminSession;
    
    public function __construct(
        BlockFactory $blockRevisionFactory,
        BlockRepository $blockRevisionRepository,
        Session $adminSession
    ) {
    
        $this->blockRevisionFactory = $blockRevisionFactory;
        $this->blockRevisionRepository = $blockRevisionRepository;
        $this->adminSession = $adminSession;
    }
    
    public function execute(Observer $observer)
    {
        $this->observer = $observer;
        
        $this->createBlockRevisionBasedOnBlockData();
    }
    
    private function createBlockRevisionBasedOnBlockData()
    {
        $this
            ->initBlockFromObserver()
            ->initNewBlockRevision()
            ->addBlockDataToBlockRevision()
            ->addAdminUserDetailsToRevision()
            ->saveBlockRevision()
        ;
        return $this;
    }
    
    private function initBlockFromObserver()
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
    
    private function initNewBlockRevision()
    {
        $this->blockRevision = $this->getNewBlockRevisionObject();
        return $this;
    }
    
    private function getNewBlockRevisionObject()
    {
        return $this->blockRevisionFactory->create();
    }
    
    private function addBlockDataToBlockRevision()
    {
        $this->blockRevision
            ->setData($this->getBlockData());
        return $this;
    }
    
    private function getBlockData()
    {
        return $this->block->getData();
    }
    
    private function addAdminUserDetailsToRevision()
    {
        $this->blockRevision
            ->setAdminUserId($this->getCurrentAdminUserId());
        return $this;
    }
    
    private function getCurrentAdminUserId()
    {
        return $this->adminSession->getUser()->getId();
    }
    
    private function saveBlockRevision()
    {
        $this->resetBlockRevisionCreationTime();
        $this->saveCurrentBlockInRepositoryAsCurrent();
        return $this;
    }
    
    private function resetBlockRevisionCreationTime()
    {
        $this->blockRevision
            ->setCreationTime(null);
        return $this;
    }
    
    private function saveCurrentBlockInRepositoryAsCurrent()
    {
        $this->blockRevisionRepository
            ->saveAsCurrent($this->blockRevision);
    }
}
