<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Yes/No source model class for grid listing
 */
class Yesno implements OptionSourceInterface
{
    
    const VALUE_YES = 1;
    const VALUE_NO = 0;

    /**
     * Retrieve all options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            ['label' => __('Yes'), 'value' => self::VALUE_YES],
            ['label' => __('No'), 'value' => self::VALUE_NO],
        ];
        return $options;
    }
}
