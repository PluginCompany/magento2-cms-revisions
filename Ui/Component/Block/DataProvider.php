<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Ui\Component\Block;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\Reporting;

class DataProvider extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param Reporting $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Reporting $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        array $meta = [],
        array $data = []
    ) {
        
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
        
        $this->addBlockIdFilter();
    }
    
    public function addBlockIdFilter()
    {
        if ($this->canFilterBlockId()) {
            $this->execAddBlockIdFilter();
        }
        return $this;
    }
    
    public function canFilterBlockId()
    {
        return (bool)$this->getCurrentBlockId();
    }
    
    public function getCurrentBlockId()
    {
        return $this->request->getParam('block_id');
    }
    
    private function execAddBlockIdFilter()
    {
        $this->searchCriteriaBuilder
            ->addFilter($this->generateBlockIdFilterObject());
        return $this;
    }
    
    private function generateBlockIdFilterObject()
    {
        return $this->filterBuilder
            ->create()
            ->setField('block_id')
            ->setValue($this->getCurrentBlockId());
    }
}
