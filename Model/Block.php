<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Model;

use Magento\Cms\Model\BlockRepository as CmsBlockRepository;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class Block extends \Magento\Framework\Model\AbstractModel implements BlockInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'plugincompany_cmsrevisions_block';

    private $block;
    private $cmsBlockRepository;
    private $blockRevisionRepository;

    protected function _construct()
    {
        $this->_init('PluginCompany\CmsRevisions\Model\ResourceModel\Block');
    }

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     * @param $blockRepository
     */
    public function __construct(
        CmsBlockRepository $cmsBlockRepository,
        BlockRepository $blockRevisionRepository,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->cmsBlockRepository = $cmsBlockRepository;
        $this->blockRevisionRepository = $blockRevisionRepository;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }


    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function restore()
    {
        $this
            ->initBlock()
            ->setRevisionDataToBlock()
            ->saveBlock()
            ->saveThisRevisionAsCurrent()
        ;
        return $this;
    }

    private function setRevisionDataToBlock()
    {
        $this
            ->getBlock()
            ->addData($this->getDataWithoutCreationTime());
        return $this;
    }


    public function getBlock()
    {
        if (!$this->block) {
            $this->initBlock();
        }
        return $this->block;
    }

    private function initBlock()
    {
        if (!$this->block) {
            $this->block = $this->tryRetrieveBlock();
        }
        return $this;
    }

    private function tryRetrieveBlock()
    {
        try {
            return $this->retrieveBlock();
        } catch (NoSuchEntityException $e) {
            $this->throwRetrieveCmsBlockError();
        }
    }

    private function retrieveBlock()
    {
        return $this->cmsBlockRepository
            ->getById($this->getBlockId());
    }

    private function throwRetrieveCmsBlockError()
    {
        throw new \Exception('There was an error retrieving the CMS block');
    }

    private function getDataWithoutCreationTime()
    {
        $data = $this->getData();
        unset($data['creation_time']);
        return $data;
    }

    private function saveBlock()
    {
        try {
            $this->executeSaveBlock();
        } catch (CouldNotSaveException $e) {
            $this->throwCmsBlockSaveError();
        }
        return $this;
    }

    private function executeSaveBlock()
    {
        $this->cmsBlockRepository
            ->save($this->getBlock());
    }

    private function throwCmsBlockSaveError()
    {
        throw new \Exception('There was an error saving the CMS block');
    }

    private function saveThisRevisionAsCurrent()
    {
        $this->blockRevisionRepository
            ->saveAsCurrent($this);
        return $this;
    }
}
