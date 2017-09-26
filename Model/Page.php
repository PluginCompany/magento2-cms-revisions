<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Model;

use Magento\Cms\Model\PageRepository as CmsPageRepository;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

class Page extends AbstractModel implements PageInterface, IdentityInterface
{
    const CACHE_TAG = 'plugincompany_cmsrevisions_page';
    
    private $cmsPage;
    private $cmsPageRepository;
    private $pageRevisionRepository;

    protected function _construct()
    {
        $this->_init('PluginCompany\CmsRevisions\Model\ResourceModel\Page');
    }

    /**
     * @param CmsPageRepository $cmsPageRepository
     * @param PageRepository $pageRevisionRepository
     * @param Context $context
     * @param Registry $registry
     * @param AbstractResource $resource
     * @param AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        CmsPageRepository $cmsPageRepository,
        PageRepository $pageRevisionRepository,
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
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
            ->initCmsPage()
            ->setRevisionDataToPage()
            ->saveCmsPage()
            ->saveThisRevisionAsCurrent()
        ;
        return $this;
    }

    private function setRevisionDataToPage()
    {
        $this
            ->getCmsPage()
            ->addData($this->getDataWithoutCreationTime());
        return $this;
    }

    private function getCmsPage()
    {
        if (!$this->cmsPage) {
            $this->initCmsPage();
        }
        return $this->cmsPage;
    }

    private function initCmsPage()
    {
        if (!$this->cmsPage) {
            $this->cmsPage = $this->retrievePage();
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
    
    private function saveCmsPage()
    {
        try {
            $this->executeSaveCmsPage();
        } catch (CouldNotSaveException $e) {
            $this->throwCmsPageSaveError();
        }
        return $this;
    }
    
    private function executeSaveCmsPage()
    {
        $this->cmsPageRepository
            ->save($this->getCmsPage());
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

    public function getCmsPageWithRevisionData()
    {
        $this->setRevisionDataToPage();
        return $this->getCmsPage();
    }

}
