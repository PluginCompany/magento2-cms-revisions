<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Helper;

use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Data extends AbstractHelper
{

    /**
     * @var ProductMetadataInterface
     */
    private $productMetadata;

    public function __construct(
        Context $context,
        ProductMetadataInterface $productMetadata
    )
    {
        $this->productMetadata = $productMetadata;
        parent::__construct($context);
    }

    public function isPageBuilderActive()
    {
        return $this->scopeConfig->isSetFlag('cms/pagebuilder/enabled');
    }

    public function shouldExplicitlyEnableTemplateRendering()
    {
        return version_compare($this->productMetadata->getVersion(), '2.4.0', '>=');
    }

}
