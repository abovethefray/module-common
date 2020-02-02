/**
 * Copyright © Above The Fray Design, Inc. All rights reserved.
 * See ATF_COPYING.txt for license details.
 */
require([
    'jquery',
    'Magento_Customer/js/customer-data'
], function ($, customerData) {
    var checked = false;

    function isGroupApplicable(customerGroup) {
        if ([2,4].indexOf(customerGroup) === -1) {
            if ($('nav.navigation > .menu [href*="/socks-b2b"]').length > 0) {
                $('body').trigger('processStart');
                setTimeout(function () {
                    location.reload(true);
                }, 100);
            }
        }

        if ([2,4].indexOf(customerGroup) > -1) {
            if ($('nav.navigation > .menu [href*="/socks/"]').length > 0) {
                $('body').trigger('processStart');
                setTimeout(function () {
                    location.reload(true);
                }, 100);
            }
        }
    }

    customerData.get('cart').subscribe(function (customerData) {
        if (!checked) {
            isGroupApplicable(parseInt(customerData.customer_group));
            checked = true;
        }
    });
});
