<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Helper;

class PageRevision extends \Magento\Cms\Helper\Page
{
    
    public function setPage($page)
    {
        $this->_page = $page;
        return $this;
    }
}
