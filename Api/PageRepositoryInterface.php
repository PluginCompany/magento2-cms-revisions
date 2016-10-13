<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Api;

use PluginCompany\CmsRevisions\Model\PageInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface PageRepositoryInterface
{
    public function save(PageInterface $page);

    public function getById($id);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(PageInterface $page);

    public function deleteById($id);
}
