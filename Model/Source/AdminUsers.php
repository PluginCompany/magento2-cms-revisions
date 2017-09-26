<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\User\Model\ResourceModel\User\CollectionFactory;

class AdminUsers implements OptionSourceInterface
{
    private $collectionFactory;
    private $optionArray;

    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Retrieve all options
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->hasOptionArray()) {
            $this->initOptionArray();
        }
        return $this->optionArray;
    }

    private function hasOptionArray()
    {
        return !empty($this->optionArray);
    }

    private function initOptionArray()
    {
        $this->optionArray = $this->getFormattedOptionArray();
    }

    private function getFormattedOptionArray()
    {
        $optionArray = [];
        foreach ($this->getOptionsCollection() as $user) {
            $optionArray[] =
                [
                    'label' => $user->getName(),
                    'value' => $user->getId()
                ];
        }
        $optionArray[] = [
            'label' => 'System',
            'value' => 5000
        ];
        return $optionArray;
    }

    private function getOptionsCollection()
    {
        return $this->collectionFactory->create();
    }

}
