<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Controller\Adminhtml\Block;

/**
 * Class Delete
 */
class Delete extends MassDelete
{
    protected function getBlockRevisionIds()
    {
        $id = $this->_request->getParam('block_revision_id');
        return [$id];
    }
}
