<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Controller\Adminhtml\Block;

use PluginCompany\CmsRevisions\Model\BlockRepository;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;

class Restore extends \Magento\Framework\App\Action\Action
{
    
    private $blockRevisionRepository;
    private $blockRevision;
    
    public function __construct(
        Context $context,
        BlockRepository $blockRevisionRepository
    ) {
    
        parent::__construct($context);
        $this->blockRevisionRepository = $blockRevisionRepository;
    }
        
    public function execute()
    {
        try {
            $this->restoreRevision();
            $this->addSuccessMessage();
        } catch (\Exception $e) {
            $this->addErrorMessage($e->getMessage());
        }
        $this->_redirect($this->_redirect->getRefererUrl());
    }
    
    private function restoreRevision()
    {
        $this->getBlockRevision()->restore();
    }
    
    private function getBlockRevision()
    {
        if (!$this->blockRevision) {
            $this->initBlockRevision();
        }
        return $this->blockRevision;
    }
    
    private function initBlockRevision()
    {
        $this->blockRevision = $this->getBlockRevisionByRequestParam();
        return $this;
    }
    
    private function getBlockRevisionByRequestParam()
    {
        try {
            return $this->retrieveRevisionFromRepoById($this->getBlockRevisionId());
        } catch (NoSuchEntityException $e) {
            $this->handleRevisionNotFoundError();
        }
    }
    
    private function retrieveRevisionFromRepoById($id)
    {
        return $this->blockRevisionRepository
            ->getById($id);
    }
    
    private function getBlockRevisionId()
    {
        return $this->_request->getParam('block_revision_id');
    }
    
    private function handleRevisionNotFoundError()
    {
        throw new \Exception('There was an error retrieving the CMS block revision');
    }
    
    private function addSuccessMessage()
    {
        $this->messageManager->addSuccessMessage('Revision restored successfully');
    }
    
    private function addErrorMessage($message)
    {
        $this->messageManager->addSuccessMessage('Something went wrong: ' . $message);
    }
}
