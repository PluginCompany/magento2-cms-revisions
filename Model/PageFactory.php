<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Model;

use Magento\Cms\Model\PageRepository as CmsPageRepository;
use Magento\Framework\ObjectManagerInterface;
use Magento\Cms\Model\Page as CmsPage;

class PageFactory extends RevisionFactory
{
    /**
     * Factory constructor
     *
     * @param ObjectManagerInterface $objectManager
     * @param CmsPageRepository $pageRepository
     * @param string $instanceName
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        CmsPageRepository $pageRepository,
        $instanceName = '\\PluginCompany\\CmsRevisions\\Model\\Page')
    {
        parent::__construct(
            $objectManager,
            $pageRepository,
            $instanceName
        );
    }

    public function createFromCmsPageId($pageId)
    {
        return $this->createFromEntityId($pageId);
    }

    public function createFromCmsPage(CmsPage $page)
    {
        return $this->createFromEntity($page);
    }

}