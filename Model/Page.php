<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Model;

use Magento\Cms\Model\PageRepository as CmsPageRepository;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class Page extends \Magento\Framework\Model\AbstractModel implements PageInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'plugincompany_cmsrevisions_page';
    
    private $page;
    private $cmsPageRepository;
    private $pageRevisionRepository;

    protected function _construct()
    {
        $this->_init('PluginCompany\CmsRevisions\Model\ResourceModel\Page');
    }
    
    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     * @param $pageRepository
     */
    public function __construct(
        CmsPageRepository $cmsPageRepository,
        PageRepository $pageRevisionRepository,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->cmsPageRepository = $cmsPageRepository;
        $this->pageRevisionRepository = $pageRevisionRepository;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }
    

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
    
    public function restore()
    {
        $this
            ->initPage()
            ->setRevisionDataToPage()
            ->savePage()
            ->saveThisRevisionAsCurrent()
        ;
        return $this;
    }
    
    public function setRevisionDataToPage()
    {
        $this
            ->getPage()
            ->addData($this->getDataWithoutCreationTime());
        return $this;
    }
    
    
    public function getPage()
    {
        if (!$this->page) {
            $this->initPage();
        }
        return $this->page;
    }
    
    private function initPage()
    {
        if (!$this->page) {
            $this->page = $this->retrievePage();
        }
        return $this;
    }
    
    private function retrievePage()
    {
        try {
            return $this->doRetrievePage();
        } catch (NoSuchEntityException $e) {
            $this->throwRetrieveCmsPageError();
        }
    }
    
    private function doRetrievePage()
    {
        return $this->cmsPageRepository
            ->getById($this->getPageId());
    }

    private function throwRetrieveCmsPageError()
    {
        throw new \Exception('There was an error retrieving the CMS page');
    }
    
    private function getDataWithoutCreationTime()
    {
        $data = $this->getData();
        unset($data['creation_time']);
        return $data;
    }
    
    private function savePage()
    {
        try {
            $this->executeSavePage();
        } catch (CouldNotSaveException $e) {
            $this->throwCmsPageSaveError();
        }
        return $this;
    }
    
    private function executeSavePage()
    {
        $this->cmsPageRepository
            ->save($this->getPage());
    }
    
    private function throwCmsPageSaveError()
    {
        throw new \Exception('There was an error saving the CMS page');
    }
    
    private function saveThisRevisionAsCurrent()
    {
        $this->pageRevisionRepository
            ->saveAsCurrent($this);
        return $this;
    }
}
