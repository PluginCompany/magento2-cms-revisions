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
    private $optionCollection;
    
    public function __construct(CollectionFactory $collectionFactory)
    {
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
        $this->initOptionCollection();
        return $this->getFormattedOptionArrayFromOptionCollection();
    }

    private function initOptionCollection()
    {
        return $this->optionCollection = $this->collectionFactory->create();
    }

    private function getFormattedOptionArrayFromOptionCollection()
    {
        $options = $this->optionCollection;
        $optionArray = [];
        foreach ($options as $user) {
            $optionArray[] =
                [
                    'label' => $user->getName(),
                    'value' => $user->getId()
                ];
        }
        return $optionArray;
    }
}
