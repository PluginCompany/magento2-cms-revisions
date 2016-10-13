<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Block\Adminhtml\Block\Grid;

class MassAction extends \Magento\Backend\Block\Widget\Grid\Massaction
{
    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        $params = array_merge($params, ['_current'=>true]);
        return $this->_urlBuilder->getUrl($route, $params);
    }
}
