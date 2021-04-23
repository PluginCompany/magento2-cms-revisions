<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Component\Adminhtml;

use Magento\Ui\Component\Listing;

use Magento\Framework\DataObject;
use Magento\Framework\View\Element\UiComponentInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class PageListing extends Listing
{

    /**
     * Constructor
     *
     * @param ContextInterface $context
     * @param UiComponentInterface[] $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        array $components = [],
        array $data = []
    ) {
        
        $this->context = $context;
        $this->components = $components;
        
        $data['config']['render_url'] = $this->getUrlWithPageId();
        $data['config']['update_url'] = $this->getUrlWithPageId();
        $data['config']['filter_url_params'] = ['page_id'];
        
        $this->initObservers($data);
        $this->context->getProcessor()->register($this);
        $this->_data = array_replace_recursive($this->_data, $data);
        
        parent::__construct($context, $components, $data);
    }
    
    private function getUrlWithPageId()
    {
        $url = $this->context->getUrl('mui/index/render', ['_current' => true]);
        return $url;
    }

}
