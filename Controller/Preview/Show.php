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
use PluginCompany\CmsRevisions\Model\PreviewToken;

class Show extends Action
{
    private $resultForwardFactory;
    private $pageRevisionRepository;
    private $previewToken;

    /**
     * @param PageRepository $pageRevisionRepository
     * @param Context $context
     * @param ForwardFactory $resultForwardFactory
     * @param PreviewToken $previewToken
     */
    public function __construct(
        PageRepository $pageRevisionRepository,
        Context $context,
        ForwardFactory $resultForwardFactory,
        PreviewToken $previewToken
    ) {
        $this->pageRevisionRepository = $pageRevisionRepository;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->previewToken = $previewToken;
        parent::__construct($context);
    }

    /**
    * View CMS page action
    *
    * @return \Magento\Framework\Controller\ResultInterface
    */
    public function execute()
    {
        if (!$this->isSignedPreviewLink()) {
            return $this->resultForwardFactory->create()->forward('noroute');
        }
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
    
    /**
     * Preview links are created in the admin revision grid and are signed and time limited
     *
     * @return bool
     */
    private function isSignedPreviewLink()
    {
        return $this->previewToken->isValid(
            $this->getRevisionId(),
            $this->_request->getParam('expires'),
            $this->_request->getParam('token')
        );
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
