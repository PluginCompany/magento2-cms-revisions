<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Ui\Component\Block;

use PluginCompany\CmsRevisions\Helper\Data;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

/**
 * Class Actions
 */
class Actions extends Column
{
    const UI_COMPONENT_PATH = 'cms_block_revisions_listing.cms_block_revisions_listing.cms_block_revision_columns.actions';

    private $urlBuilder;
    /**
     * @var Data
     */
    private $helper;

    /**
     * @param ContextInterface $context
     * @param Data $helper
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        Data $helper,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->helper = $helper;
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
                if (isset($item['block_revision_id'])) {
                    if(!$this->isPageBuilderEnabled()) {
                        $item[$name]['show_in_editor'] = $this->getShowInEditorButton($item);
                    }
                    $item[$name]['restore'] = $this->getRestoreButton($item);
                    $item[$name]['delete'] = $this->getDeleteButton($item);
                }
            }
        }

        return $dataSource;
    }

    private function isPageBuilderEnabled()
    {
        return $this->helper->isPageBuilderActive();

    }

    public function getShowInEditorButton($item)
    {
        return
            [
                'label' => __('Edit'),
                'callback' => [
                    'provider' => SELF::UI_COMPONENT_PATH,
                    'target' => 'showInEditor'
                ],
                'confirm' => [
                    'title' => __('Edit "${ $.$data.title }" revision in current editor'),
                    'message' => __('Are you sure you want to restore this revision in the editor? This will replace the existing contents.')
                ],
                'revision' => $item
            ];
    }

    public function getRestoreButton($item)
    {
        return
            [
                'href' => $this->urlBuilder->getUrl('cmsrevisions/block/restore', ['block_revision_id' => $item['block_revision_id']]),
                'label' => __('Restore'),
                'confirm' => [
                    'title' => __('Restore ${ $.$data.title }'),
                    'message' => __('Are you sure you want to restore this revision?')
                ]
            ];
    }

    public function getDeleteButton($item)
    {
        return
            [
                'href' => $this->urlBuilder->getUrl('cmsrevisions/block/delete', ['block_revision_id' => $item['block_revision_id']]),
                'label' => __('Delete'),
                'confirm' => [
                    'title' => __('Delete ${ $.$data.title }'),
                    'message' => __('Are you sure you want to delete this revision?')
                ]
            ];
    }
}
