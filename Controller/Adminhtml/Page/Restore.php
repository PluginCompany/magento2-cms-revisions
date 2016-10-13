<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Controller\Adminhtml\Page;

use PluginCompany\CmsRevisions\Model\PageRepository;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;

class Restore extends \Magento\Framework\App\Action\Action
{
    
    private $pageRevisionRepository;
    private $pageRevision;
    
    public function __construct(
        Context $context,
        PageRepository $pageRevisionRepository
    ) {
    
        parent::__construct($context);
        $this->pageRevisionRepository = $pageRevisionRepository;
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
        $this->getPageRevision()->restore();
    }
    
    private function getPageRevision()
    {
        if (!$this->pageRevision) {
            $this->initPageRevision();
        }
        return $this->pageRevision;
    }
    
    private function initPageRevision()
    {
        $this->pageRevision = $this->getPageRevisionByRequestParam();
        return $this;
    }
    
    private function getPageRevisionByRequestParam()
    {
        try {
            return $this->retrieveRevisionFromRepoById($this->getPageRevisionId());
        } catch (NoSuchEntityException $e) {
            $this->handleRevisionNotFoundError();
        }
    }
    
    private function retrieveRevisionFromRepoById($id)
    {
        return $this->pageRevisionRepository
            ->getById($id);
    }
    
    private function getPageRevisionId()
    {
        return $this->_request->getParam('page_revision_id');
    }
    
    private function handleRevisionNotFoundError()
    {
        throw new \Exception('There was an error retrieving the CMS page revision');
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
