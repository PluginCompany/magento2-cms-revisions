<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Ui\Component\Block;

use Magento\Framework\AuthorizationInterface;
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
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * @param ContextInterface $context
     * @param Data $helper
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param AuthorizationInterface $authorization
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        Data $helper,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        AuthorizationInterface $authorization,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->helper = $helper;
        $this->authorization = $authorization;
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
        foreach ($dataSource['data']['items'] as &$item) {
            if (!isset($item['block_revision_id'])) {
                continue;
            }
            $name = $this->getData('name');
            if($this->isRestoreAllowed()) {
                if(!$this->isPageBuilderEnabled()) {
                    $item[$name]['show_in_editor'] = $this->getShowInEditorButton($item);
                }
                $item[$name]['restore'] = $this->getRestoreButton($item);
            }
            if($this->isDeleteAllowed()) {
                $item[$name]['delete'] = $this->getDeleteButton($item);
            }
        }
        return $dataSource;
    }

    private function isDeleteAllowed()
    {
        return $this->authorization->isAllowed("PluginCompany_CmsRevisions::block_delete");
    }

    private function isRestoreAllowed()
    {
        return $this->authorization->isAllowed("PluginCompany_CmsRevisions::block_restore");
    }


    private function isPageBuilderEnabled()
    {
        return $this->helper->isPageBuilderActive();

    }

    public function getShowInEditorButton($item)
    {
       $button =
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
       if($this->helper->shouldExplicitlyEnableTemplateRendering()) {
           $button['confirm']['__disableTmpl'] = ['title' => false, 'message' => false];
       }
       return $button;
    }

    public function getRestoreButton($item)
    {
        $button =
            [
                'href' => $this->urlBuilder->getUrl('cmsrevisions/block/restore', ['block_revision_id' => $item['block_revision_id']]),
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

    public function getDeleteButton($item)
    {
        $button =
            [
                'href' => $this->urlBuilder->getUrl('cmsrevisions/block/delete', ['block_revision_id' => $item['block_revision_id']]),
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
