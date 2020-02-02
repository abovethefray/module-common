<?php
/**
 * Copyright © Above The Fray Design, Inc. All rights reserved.
 * See ATF_COPYING.txt for license details.
 */

namespace ATF\Common\Block\Adminhtml\System\Config\Fieldset;

/**
 * Class Hint
 * @package ATF\Common\Block\Adminhtml\System\Config\Fieldset
 */
class Hint extends \Magento\Backend\Block\Template implements \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
    /**
     * @var string
     */
    protected $_template = 'ATF_Common::system/config/fieldset/hint.phtml';

    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return mixed
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->toHtml();
    }
}
