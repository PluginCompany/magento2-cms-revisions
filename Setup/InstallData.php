<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Setup;

use Magento\Cms\Model\ResourceModel\Page\CollectionFactory as PageCollectionFactory;
use Magento\Cms\Model\ResourceModel\Block\CollectionFactory as BlockCollectionFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use PluginCompany\CmsRevisions\Model\BlockFactory;
use PluginCompany\CmsRevisions\Model\BlockRepository;
use PluginCompany\CmsRevisions\Model\PageFactory;
use PluginCompany\CmsRevisions\Model\PageRepository;

class InstallData implements InstallDataInterface
{
    /** @var  \Magento\Cms\Model\ResourceModel\Page\Collection */
    private $cmsPageCollection;

    /** @var  \Magento\Cms\Model\ResourceModel\Block\Collection */
    private $cmsBlockCollection;

    /** @var PageFactory */
    private $pageRevisionFactory;

    /** @var BlockFactory */
    private $blockRevisionFactory;

    /** @var PageRepository */
    protected $pageRevisionRepository;

    /** @var BlockRepository */
    protected $blockRevisionRepository;

    /**
     * InstallData constructor.
     * @param PageCollectionFactory $cmsPageCollectionFactory
     * @param BlockCollectionFactory $cmsBlockCollectionFactory
     * @param PageFactory $pageRevisionFactory
     * @param BlockFactory $blockRevisionFactory
     * @param PageRepository $pageRevisionRepository
     * @param BlockRepository $blockRevisionRepository
     */
    public function __construct(
        PageCollectionFactory $cmsPageCollectionFactory,
        BlockCollectionFactory $cmsBlockCollectionFactory,
        PageFactory $pageRevisionFactory,
        BlockFactory $blockRevisionFactory,
        PageRepository $pageRevisionRepository,
        BlockRepository $blockRevisionRepository
    ) {
        $this->cmsBlockCollection = $cmsBlockCollectionFactory->create();
        $this->cmsPageCollection = $cmsPageCollectionFactory->create();
        $this->pageRevisionFactory = $pageRevisionFactory;
        $this->blockRevisionFactory = $blockRevisionFactory;
        $this->pageRevisionRepository = $pageRevisionRepository;
        $this->blockRevisionRepository = $blockRevisionRepository;
    }

    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $this
            ->createPageRevisions()
            ->createBlockRevisions()
        ;
    }

    private function createPageRevisions()
    {
        foreach($this->cmsPageCollection as $page){
            $this->pageRevisionRepository->saveAsCurrent(
                $this->pageRevisionFactory
                    ->createFromCmsPage($page)
                    ->setAdminUserId(5000)
            );
        }
        return $this;
    }

    private function createBlockRevisions()
    {
        foreach($this->cmsBlockCollection as $block){
            $this->blockRevisionRepository->saveAsCurrent(
                $this->blockRevisionFactory
                    ->createFromCmsBlock($block)
                    ->setAdminUserId(5000)
            );
        }
        return $this;
    }
}
