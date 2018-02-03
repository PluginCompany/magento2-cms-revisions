<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Controller\Adminhtml\Page;

use Magento\Backend\App\Action\Context;
use PluginCompany\CmsRevisions\Model\PageRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Class MassDelete
 */
class MassDelete extends \Magento\Backend\App\Action
{
    private $pageRevisionRepository;
    private $successfullyDeletedCount;
    private $failedToDeleteCount;

    /**
     * MassDelete constructor.
     * @param Context $context
     * @param PageRepository $pageRevisionRepository
     */
    public function __construct(Context $context, PageRepository $pageRevisionRepository)
    {
        $this->pageRevisionRepository = $pageRevisionRepository;
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $this
            ->deletePageRevisions()
            ->addMessages()
        ;
        
        return $this->_redirect($this->_redirect->getRefererUrl());
    }
    
    private function deletePageRevisions()
    {
        foreach ($this->getPageRevisionIds() as $id) {
            $this->tryDeleteRevisionById($id);
        }
        return $this;
    }
    
    protected function getPageRevisionIds()
    {
        if ($this->shouldDeleteAllRevisions()) {
            return $this->getAllRevisionIdsForPage();
        }
        return $this->getSelectedRevisionIds();
    }

    private function shouldDeleteAllRevisions()
    {
        return $this->_request
            ->getParam('excluded') == "false";
    }

    private function getAllRevisionIdsForPage()
    {
        return $this->pageRevisionRepository
            ->getRevisionIdsByPageId($this->getPageId());
    }
    
    private function getPageId()
    {
        return $this->_request->getParam('page_id');
    }

    private function getSelectedRevisionIds()
    {
        $ids = $this->_request->getParam('selected');
        if (is_array($ids)) {
            return $ids;
        }
        $excluded = $this->_request->getParam('excluded');
        if(is_array($excluded)) {
            return $this->getAllRevisionIdsExcluding($excluded);
        }
        return [];
    }

    /**
     * @param array $excluded
     * @return array
     */
    private function getAllRevisionIdsExcluding($excluded)
    {
        $allIds = $this->getAllRevisionIdsForPage();
        return array_diff($allIds, $excluded);
    }

    private function tryDeleteRevisionById($id)
    {
        try {
            $this->deletePageRevisionById($id);
        } catch (NoSuchEntityException $e) {
            $this->handleError($e);
        } catch (CouldNotDeleteException $e) {
            $this->handleError($e);
        }
        return $this;
    }
    
    private function deletePageRevisionById($id)
    {
        $this->pageRevisionRepository->deleteById($id);
        $this->successfullyDeletedCount++;
        return $this;
    }
    
    private function handleError($e)
    {
        $this->failedToDeleteCount++;
        //TODO add logging
    }
    
    private function addMessages()
    {
        if ($this->hasSuccesses()) {
            $this->addSuccessMessages();
        }
        if ($this->hasErrors()) {
            $this->addErrorMessages();
        }
        return $this;
    }
    
    private function hasSuccesses()
    {
        return (bool)$this->successfullyDeletedCount;
    }

    private function addSuccessMessages()
    {
        $this->messageManager
            ->addSuccessMessage(__('A total of %1 record(s) have been deleted.', 
                $this->successfullyDeletedCount));
        return $this;
    }
    
    private function hasErrors()
    {
        return (bool)$this->failedToDeleteCount;
    }
    
    private function addErrorMessages()
    {
        
        $this->messageManager
            ->addErrorMessage(__('A total of %1 record(s) could not be deleted. Please review logs.', 
                $this->failedToDeleteCount));
        return $this;
    }
}
