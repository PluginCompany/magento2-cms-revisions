<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Store\Model\StoreManagerInterface;

class Stores implements OptionSourceInterface
{
    private $storeManagerInterface;
    private $storeArray;
    private $storeCollection;
    
    public function __construct(StoreManagerInterface $storeManagerInterface)
    {
        $this->storeManagerInterface = $storeManagerInterface;
    }

    /**
     * Retrieve all options
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->hasStoreArray()) {
            $this->initStoreArray();
        }
        return $this->storeArray;
    }

    private function hasStoreArray()
    {
        return !empty($this->storeArray);
    }

    private function initStoreArray()
    {
        $this->storeArray = $this->getFormattedStoreArray();
    }

    private function getFormattedStoreArray()
    {
        $this->initStoreCollection();
        return $this->getFormattedStoreArrayFromStoreCollection();
    }

    private function initStoreCollection()
    {
        return $this->storeCollection = $this->storeManagerInterface->getStores();
    }

    private function getFormattedStoreArrayFromStoreCollection()
    {
        $stores = $this->storeCollection;

        $storeArray = [];
        foreach ($stores as $storeId => $store) {
            /** @var $store \Magento\Store\Model\Store */
            $storeArray[$storeId] =
                [
                    'label' => $store->getName(),
                    'value' => $store->getId(),
                    'previewUrl' => $store->getBaseUrl() . 'previewcms/preview/show/'
                ];
        }
        return $storeArray;
    }
}
