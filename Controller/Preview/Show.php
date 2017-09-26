<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Controller\Preview;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\ForwardFactory;
use PluginCompany\CmsRevisions\Model\PageRepository;

class Show extends Action
{
    private $resultForwardFactory;
    private $pageRevisionRepository;

    /**
     * @param PageRepository $pageRevisionRepository
     * @param Context $context
     * @param ForwardFactory $resultForwardFactory
     */
    public function __construct(
        PageRepository $pageRevisionRepository,
        Context $context,
        ForwardFactory $resultForwardFactory
    ) {
        $this->pageRevisionRepository = $pageRevisionRepository;
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    /**
    * View CMS page action
    *
    * @return \Magento\Framework\Controller\ResultInterface
    */
    public function execute()
    {
        $resultPage = $this
            ->_objectManager
            ->get('PluginCompany\CmsRevisions\Helper\PageRevision')
            ->setPage($this->getPageWithRevisionData())
            ->prepareResultPage($this);
        $this->addPageToLayoutBlock();
        if (!$resultPage) {
            $resultForward = $this->resultForwardFactory->create();
            return $resultForward->forward('noroute');
        }
        return $resultPage;
    }

    /**
     * @return \PluginCompany\CmsRevisions\Model\Page
     */
    private function getPageRevision()
    {
        return $this->pageRevisionRepository->getById($this->getRevisionId());
    }
    
    private function getRevisionId()
    {
        return $this->_request->getParam('page_revision_id');
    }
    
    private function addPageToLayoutBlock()
    {
        $layout = $this->_view->getLayout();

        $layout->getBlock('cms_page')->setPage($this->getPageWithRevisionData());
    }
    
    private function getPageWithRevisionData()
    {
        return $this
            ->getPageRevision()
            ->getCmsPageWithRevisionData();
    }
}
