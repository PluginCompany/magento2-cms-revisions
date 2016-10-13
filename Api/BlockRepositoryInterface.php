<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Api;

use PluginCompany\CmsRevisions\Model\BlockInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface BlockRepositoryInterface
 * @package PluginCompany\CmsRevisions\Api
 */
interface BlockRepositoryInterface
{
    /**
     * @param BlockInterface $page
     * @return mixed
     */
    public function save(BlockInterface $page);

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * @param SearchCriteriaInterface $criteria
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $criteria);

    /**
     * @param BlockInterface $page
     * @return mixed
     */
    public function delete(BlockInterface $page);

    /**
     * @param $id
     * @return mixed
     */
    public function deleteById($id);
}
