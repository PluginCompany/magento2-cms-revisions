<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Model;

use Magento\Framework\ObjectManagerInterface;
use Magento\Cms\Model\Block as CmsBlock;
use Magento\Cms\Model\BlockRepository as CmsBlockRepository;

class BlockFactory extends RevisionFactory
{
    /**
     * Factory constructor
     *
     * @param ObjectManagerInterface $objectManager
     * @param CmsBlockRepository $blockRepository
     * @param string $instanceName
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        CmsBlockRepository $blockRepository,
        $instanceName = '\\PluginCompany\\CmsRevisions\\Model\\Block')
    {
        parent::__construct(
            $objectManager,
            $blockRepository,
            $instanceName
        );
    }

    public function createFromCmsBlockId($blockId)
    {
        return $this->createFromEntityId($blockId);
    }

    public function createFromCmsBlock(CmsBlock $block)
    {
        return $this->createFromEntity($block);
    }

}