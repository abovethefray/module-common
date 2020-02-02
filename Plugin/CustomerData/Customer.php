<?php

namespace ATF\Common\Plugin\CustomerData;

use Magento\Checkout\CustomerData\Cart;
use Magento\Customer\Model\Session as CustomerSession;

class Customer
{
    protected $_session;

    public function __construct(CustomerSession $session)
    {
        $this->_session = $session;
    }

    public function afterGetSectionData(Cart $subject, $result)
    {
        $customerGroup = 0;

        if ($this->_session->isLoggedIn()) {
            $customerGroup = $this->_session->getCustomer()->getGroupId();
        }

        $result['customer_group'] = $customerGroup;
        return $result;
    }
}
