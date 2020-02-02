<?php
namespace ATF\Common\Helper;

use Magento\Catalog\Helper\Output;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Product extends AbstractHelper
{
    const SHASHI_COLLECTION_AT_CODE = 'shashi_collection';

    protected $_helper;

    public function __construct(
        Output $helper,
        Context $context
    ) {
        $this->_helper = $helper;
        parent::__construct($context);
    }

    public function getSHASHICollectionValues(\Magento\Catalog\Model\Product $_product)
    {
        $values = false;
        $_attributeValues = $this->_helper->productAttribute(
            $_product,
            $_product->getShashiCollection(),
            self::SHASHI_COLLECTION_AT_CODE
        );
        if ($_attributeValues) {
            $values = '';
            $_attribute = $_product->getResource()->getAttribute(self::SHASHI_COLLECTION_AT_CODE);
            foreach (explode(',', $_attributeValues) as $value) {
                if ($_attribute->usesSource()) {
                    $values .= $_attribute->getSource()->getOptionText($value) . '&nbsp;';
                }
            }
        }

        return $values;
    }
}
