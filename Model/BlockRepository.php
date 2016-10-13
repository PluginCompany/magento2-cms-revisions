<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Model;

use PluginCompany\CmsRevisions\Model\ResourceModel\Block\CollectionFactory;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Api\SearchResultsInterfaceFactory;

class BlockRepository implements \PluginCompany\CmsRevisions\Api\BlockRepositoryInterface
{
    private $objectFactory;
    private $collectionFactory;
    private $searchResultsFactory;
    
    public function __construct(
        BlockFactory $objectFactory,
        CollectionFactory $collectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory
    ) {
    
        $this->objectFactory        = $objectFactory;
        $this->collectionFactory    = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
    }
    
    public function save(BlockInterface $object)
    {
        try {
            $object->save();
        } catch (Exception $e) {
            throw new CouldNotSaveException($e->getMessage());
        }
        return $object;
    }

    public function getById($id)
    {
        $object = $this->objectFactory->create();
        $object->load($id);
        if (!$object->getId()) {
            throw new NoSuchEntityException(__('Object with id "%1" does not exist.', $id));
        }
        return $object;
    }

    public function delete(BlockInterface $object)
    {
        try {
            $object->delete();
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    public function deleteById($id)
    {
        return $this->delete($this->getById($id));
    }

    public function getList(SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $collection = $this->collectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            $fields = [];
            $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
                $fields[] = $filter->getField();
                $conditions[] = [$condition => $filter->getValue()];
            }
            if ($fields) {
                $collection->addFieldToFilter($fields, $conditions);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        $objects = [];
        foreach ($collection as $objectModel) {
            $objects[] = $objectModel;
        }
        $searchResults->setItems($objects);
        return $searchResults;
    }

    public function getCurrentRevisionsByBlock($block)
    {
        return $this->getCurrentRevisionsByBlockId($block->getId());
    }
    
    public function getCurrentRevisionsByBlockId($blockId)
    {
        $collection = $this->getRevisionsByBlockId($blockId);
        $collection
            ->addFieldToFilter('is_current_revision', true);
        return $collection;
    }

    public function getRevisionsByBlockId($blockId)
    {
        $collection = $this->_getNewCollection();
        $collection
            ->addFieldToFilter('block_id', $blockId);
        return $collection;
    }
    
    public function getRevisionIdsByBlockId($blockId)
    {
        $collection = $this
            ->getRevisionsByBlockId($blockId)
            ->getColumnValues('block_revision_id');
        return $collection;
    }
    
    protected function _getNewCollection()
    {
        return $this->collectionFactory->create();
    }
    
    public function saveAsCurrent($revision)
    {
        $this->_setAllRevisionsAsNotCurrentByBlockId($revision->getBlockId());
        $this->_saveAsCurrent($revision);
    }
    
    protected function _setAllRevisionsAsNotCurrentByBlockId($blockId)
    {
        $revisions = $this->getCurrentRevisionsByBlockId($blockId);
        foreach ($revisions as $revision) {
            $this->_saveRevisionAsNotCurrent($revision);
        }
    }
    
    protected function _saveRevisionAsNotCurrent($revision)
    {
        $revision->setIsCurrentRevision(false);
        $this->save($revision);
    }
    
    protected function _saveAsCurrent($revision)
    {
        $revision->setIsCurrentRevision(true);
        $this->save($revision);
    }
}
