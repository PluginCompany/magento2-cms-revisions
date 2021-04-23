<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Ui\Component\Page;

use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Form\Fieldset as BaseFieldset;

class Fieldset extends BaseFieldset
{

    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    public function __construct(
        ContextInterface $context,
        AuthorizationInterface $authorization,
        array $components = [],
        array $data = []
    ) {
        $this->authorization = $authorization;
        parent::__construct($context, $components, $data);
    }

    public function prepare()
    {
        $config = $this->getData('config');
        $config['visible'] = (bool)$this->authorization->isAllowed("PluginCompany_CmsRevisions::page");
        $this->setData('config', $config);
        return parent::prepare();
    }


}