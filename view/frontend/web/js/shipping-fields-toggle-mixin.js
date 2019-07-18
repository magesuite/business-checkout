define([
    'jquery',
    'underscore',
    'ko',
], function ($, _, ko) {
    'use strict';

    var isEnabled = Boolean(window.checkoutConfig.business_checkout_config === '1');

    return function(Shipping) {
        return isEnabled ? Shipping.extend({
            initialize: function() {
                this._super();
                
                var businessOnlyFields = ['company','vat_id'];
                doWhenFieldsReady(this.elems,'shipping-address-fieldset', handler);

                function handler(fieldset) {
                    var customerType = _.findWhere(fieldset.elems(), {index: 'customer_type'});
                    toggleFields(fieldset, customerType.value());
                    customerType.value.subscribe(function(value) {
                        toggleFields(fieldset, value);
                     });
                }
            
                function toggleFields(fieldset, value) {
                    var isBusinessCheckout;

                    if (value === 'private') { 
                        isBusinessCheckout = false;
                    } else if (value === 'business') {
                        isBusinessCheckout = true;
                    }
            
                    businessOnlyFields.forEach(function(fieldIndex) {
                        var field = _.findWhere(fieldset.elems(), {index: fieldIndex});
                        field.visible(isBusinessCheckout);
                    });
                }
            
                function doWhenFieldsReady(koElems, fieldIndex, callback) {
                    var subscription = koElems.subscribe(function(elems) {
                        var lastItem = elems[elems.length-1];
                        if (lastItem.index === fieldIndex) {
                            callback(lastItem);
                            subscription.dispose();
                        }
                    });
                }
            }    
        }) : Shipping;
    };
});
