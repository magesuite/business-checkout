define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
    'use strict';

    return function (setShippingInformationAction) {

        return wrapper.wrap(setShippingInformationAction, function (originalAction) {
            var shippingAddress = quote.shippingAddress();
            if (shippingAddress['extension_attributes'] === undefined) {
                shippingAddress['extension_attributes'] = {};
            }

            if (shippingAddress.customAttributes !== undefined && shippingAddress.customAttributes.customer_type !== undefined) {
                if (shippingAddress.customAttributes.customer_type.value !== undefined) {
                    shippingAddress['extension_attributes']['customer_type'] = shippingAddress.customAttributes['customer_type']['value'];
                }else{
                    shippingAddress['extension_attributes']['customer_type'] = shippingAddress.customAttributes['customer_type'];
                }
            }

            return originalAction();
        });
    };
});