<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Ui\Component\Page;

use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

use PluginCompany\CmsRevisions\Helper\Data;
use PluginCompany\CmsRevisions\Model\Source\Stores;
use PluginCompany\CmsRevisions\Model\PreviewToken;

/**
 * Class Actions
 */
class Actions extends Column
{
    const UI_COMPONENT_PATH = 'cms_page_revisions_listing.cms_page_revisions_listing.cms_page_columns.actions';
    
    private $urlBuilder;
    private $storeSource;
    /**
     * @var Data
     */
    private $helper;
    /**
     * @var AuthorizationInterface
     */
    private $authorization;
    /**
     * @var PreviewToken
     */
    private $previewToken;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param Data $helper
     * @param AuthorizationInterface $authorization
     * @param Stores $storeSource
     * @param PreviewToken $previewToken
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        Data $helper,
        AuthorizationInterface $authorization,
        Stores $storeSource,
        PreviewToken $previewToken,
        array $components = [],
        array $data = []
    ) {
        $this->storeSource = $storeSource;
        $this->urlBuilder = $urlBuilder;
        $this->helper = $helper;
        $this->authorization = $authorization;
        $this->previewToken = $previewToken;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }
        foreach ($dataSource['data']['items'] as & $item) {
            if (!isset($item['page_revision_id'])) {
                continue;
            }
            $name = $this->getData('name');
            if($this->isRestoreAllowed()) {
                $item[$name]['restore'] = $this->getRestoreButton($item);
                $item[$name]['preview'] = $this->getPreviewButton($item);
            }
            if($this->isDeleteAllowed()){
                $item[$name]['delete'] = $this->getDeleteButton($item);
            }
        }
        return $dataSource;
    }

    private function isDeleteAllowed()
    {
        return $this->authorization->isAllowed("PluginCompany_CmsRevisions::page_delete");
    }

    private function isRestoreAllowed()
    {
        return $this->authorization->isAllowed("PluginCompany_CmsRevisions::page_restore");
    }


    public function getRestoreButton($item)
    {
        $button =
            [
                'href' => $this->urlBuilder->getUrl('cmsrevisions/page/restore', ['page_revision_id' => $item['page_revision_id']]),
                'label' => __('Restore'),
                'confirm' => [
                    'title' => __('Restore ${ $.$data.title }'),
                    'message' => __('Are you sure you want to restore this revision?')
                ]
            ];
        if($this->helper->shouldExplicitlyEnableTemplateRendering()) {
            $button['confirm']['__disableTmpl'] = ['title' => false, 'message' => false];
        }
        return $button;
    }
    
    public function getPreviewButton($item)
    {
        $signature = $this->previewToken->create($item['page_revision_id']);
        return
            [
                'label' => __('Preview'),
                'callback' => [
                    'provider' => SELF::UI_COMPONENT_PATH,
                    'target' => 'preview'
                ],
                'stores' => $this->storeSource->toOptionArray(),
                'revisionId' => $item['page_revision_id'],
                'previewExpires' => $signature['expires'],
                'previewToken' => $signature['token']
            ];
    }
    
    public function getDeleteButton($item)
    {
        $button =
            [
                'href' => $this->urlBuilder->getUrl('cmsrevisions/page/delete', ['page_revision_id' => $item['page_revision_id']]),
                'label' => __('Delete'),
                'confirm' => [
                    'title' => __('Delete ${ $.$data.title }'),
                    'message' => __('Are you sure you want to delete this revision?')
                ]
            ];
        if($this->helper->shouldExplicitlyEnableTemplateRendering()) {
            $button['confirm']['__disableTmpl'] = ['title' => false, 'message' => false];
        }
        return $button;
    }
}
