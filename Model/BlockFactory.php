<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Model;

use Magento\Framework\ObjectManagerInterface;
use Magento\Cms\Model\Block as cmsBlock;
use Magento\Cms\Model\BlockRepository;

class BlockFactory extends RevisionFactory
{
    /**
     * Factory constructor
     *
     * @param ObjectManagerInterface $objectManager
     * @param BlockRepository $blockRepository
     * @param string $instanceName
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        BlockRepository $blockRepository,
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

    public function createFromCmsBlock(cmsBlock $block)
    {
        return $this->createFromEntity($block);
    }

}