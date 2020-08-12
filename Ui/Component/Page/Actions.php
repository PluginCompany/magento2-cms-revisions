<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Ui\Component\Page;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Ui\Component\Listing\Column\Store;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

use PluginCompany\CmsRevisions\Model\Source\Stores;

/**
 * Class Actions
 */
class Actions extends Column
{
    const UI_COMPONENT_PATH = 'cms_page_revisions_listing.cms_page_revisions_listing.cms_page_columns.actions';
    
    private $urlBuilder;
    private $storeSource;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     * @param Stores $storeSource
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        Stores $storeSource
    ) {
        $this->storeSource = $storeSource;
        $this->urlBuilder = $urlBuilder;
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
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['page_revision_id'])) {
                    $item[$name]['restore'] = $this->getRestoreButton($item);
                    $item[$name]['preview'] = $this->getPreviewButton($item);
                    $item[$name]['delete'] = $this->getDeleteButton($item);
                }
            }
        }

        return $dataSource;
    }
    
    public function getRestoreButton($item)
    {
        return
            [
                'href' => $this->urlBuilder->getUrl('cmsrevisions/page/restore', ['page_revision_id' => $item['page_revision_id']]),
                'label' => __('Restore'),
                'confirm' => [
                    'title' => __('Restore ${ $.$data.title }'),
                    'message' => __('Are you sure you want to restore this revision?'),
                    '__disableTmpl' => ['title' => false, 'message' => false]
                ]
            ];
    }
    
    public function getPreviewButton($item)
    {
        return
            [
                'label' => __('Preview'),
                'callback' => [
                    'provider' => SELF::UI_COMPONENT_PATH,
                    'target' => 'preview'
                ],
                'stores' => $this->storeSource->toOptionArray(),
                'revisionId' => $item['page_revision_id']
            ];
    }
    
    public function getDeleteButton($item)
    {
        return
            [
                'href' => $this->urlBuilder->getUrl('cmsrevisions/page/delete', ['page_revision_id' => $item['page_revision_id']]),
                'label' => __('Delete'),
                'confirm' => [
                    'title' => __('Delete ${ $.$data.title }'),
                    'message' => __('Are you sure you want to delete this revision?'),
                    '__disableTmpl' => ['title' => false, 'message' => false]
                ]
            ];
    }
}
