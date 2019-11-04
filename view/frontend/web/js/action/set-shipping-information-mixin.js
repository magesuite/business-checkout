define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
    'use strict';

    return function (setShippingInformationAction) {

        return wrapper.wrap(setShippingInformationAction, function (originalAction) {
            var shippingAddress = quote.shippingAddress();
            
            if (shippingAddress.customAttributes &&
                shippingAddress.customAttributes.customer_type) {
                shippingAddress.extension_attributes = $.extend(
                    {},
                    shippingAddress.extension_attributes,
                    {
                        customer_type: shippingAddress.customAttributes.customer_type
                    }
                );
            }

            return originalAction();
        });
    };
});