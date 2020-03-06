<?php

namespace ATF\Common\Plugin\Framework\App\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Value extends \Magento\Framework\App\Config\Value
{
    public function getOldValue()
    {
        $value = $this->_config->getValue(
            $this->getPath(),
            $this->getScope() ?: ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            $this->getScopeCode()
        );

        return is_array($value) ? $value : (string) $value;
    }
}
