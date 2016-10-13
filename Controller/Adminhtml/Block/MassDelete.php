<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Controller\Adminhtml\Block;

use Magento\Backend\App\Action\Context;
use PluginCompany\CmsRevisions\Model\BlockRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Class MassDelete
 */
class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var BlockRepository
     */
    private $blockRevisionRepository;
    /**
     * @var Successfully Revision Deleted Count
     */
    private $successfullyDeletedCount;
    /**
     * @var Revision Failed to Delete Count
     */
    private $failedToDeleteCount;

    /**
     * MassDelete constructor.
     * @param Context $context
     * @param BlockRepository $blockRevisionRepository
     */
    public function __construct(Context $context, BlockRepository $blockRevisionRepository)
    {
        $this->blockRevisionRepository = $blockRevisionRepository;
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
            ->deleteBlockRevisions()
            ->addMessages()
        ;
        
        return $this->_redirect($this->_redirect->getRefererUrl());
    }

    /**
     * @return $this
     */
    private function deleteBlockRevisions()
    {
        foreach ($this->getBlockRevisionIds() as $id) {
            $this->tryDeleteRevisionById($id);
        }
        return $this;
    }

    /**
     * @return array|mixed
     */
    protected function getBlockRevisionIds()
    {
        if ($this->shouldDeleteAllRevisions()) {
            return $this->getAllRevisionIdsForBlock();
        }
        return $this->getSelectedRevisionIds();
    }

    /**
     * @return bool
     */
    private function shouldDeleteAllRevisions()
    {
        return $this->_request
            ->getParam('excluded') == "false";
    }

    /**
     * @return array
     */
    private function getAllRevisionIdsForBlock()
    {
        return $this->blockRevisionRepository
            ->getRevisionIdsByBlockId($this->getBlockId());
    }

    /**
     * @return mixed
     */
    private function getBlockId()
    {
        return $this->_request->getParam('block_id');
    }

    /**
     * @return array|mixed
     */
    private function getSelectedRevisionIds()
    {
        $ids = $this->_request->getParam('selected');
        if (is_array($ids)) {
            return $ids;
        }
        return [];
    }

    /**
     * @param $id
     * @return $this
     */
    private function tryDeleteRevisionById($id)
    {
        try {
            $this->deleteBlockRevisionById($id);
        } catch (NoSuchEntityException $e) {
            $this->handleError($e);
        } catch (CouldNotDeleteException $e) {
            $this->handleError($e);
        }
        return $this;
    }

    /**
     * @param $id
     * @return $this
     */
    private function deleteBlockRevisionById($id)
    {
        $this->blockRevisionRepository->deleteById($id);
        $this->successfullyDeletedCount++;
        return $this;
    }

    /**
     * @param $e
     */
    private function handleError($e)
    {
        $this->failedToDeleteCount++;
        //TODO add logging
    }

    /**
     * @return $this
     */
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

    /**
     * @return bool
     */
    private function hasSuccesses()
    {
        return (bool)$this->successfullyDeletedCount;
    }

    /**
     * @return $this
     */
    private function addSuccessMessages()
    {
        $this->messageManager
            ->addSuccessMessage(__('A total of %1 record(s) have been deleted.',
                $this->successfullyDeletedCount));
        return $this;
    }

    /**
     * @return bool
     */
    private function hasErrors()
    {
        return (bool)$this->failedToDeleteCount;
    }

    /**
     * @return $this
     */
    private function addErrorMessages()
    {
        
        $this->messageManager
            ->addErrorMessage(__('A total of %1 record(s) could not be deleted. Please review logs.', 
                $this->failedToDeleteCount));
        return $this;
    }
}
