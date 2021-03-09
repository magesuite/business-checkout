define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote',
    'underscore'
], function ($, wrapper, quote, _) {
    'use strict';

    return function (setShippingInformationAction) {

        return wrapper.wrap(setShippingInformationAction, function (originalAction) {
            var shippingAddress = quote.shippingAddress();

            if (shippingAddress.customAttributes) {
                var customerType = _.findWhere(shippingAddress.customAttributes, {attribute_code: 'customer_type'});

                if (customerType) {
                    var customerTypeValue = customerType.value;

                    if (customerTypeValue) {
                        shippingAddress.extension_attributes = $.extend(
                            {},
                            shippingAddress.extension_attributes,
                            {
                                customer_type: customerTypeValue
                            }
                        );
                    }
                }
            }

            return originalAction();
        });
    };
});
