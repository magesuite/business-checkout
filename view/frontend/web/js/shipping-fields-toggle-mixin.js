define([
    'jquery',
    'underscore',
    'ko',
], function ($, _, ko) {
    'use strict';

    var isBusinessCheckout = Boolean(window.checkoutConfig.businessCheckoutEnabled === '1');

    function toggleFields(fieldset) {
        var companyField = _.findWhere(fieldset.elems(), {index: 'company'});
        var vatField = _.findWhere(fieldset.elems(), {index: 'vat_id'});
        companyField.visible(isBusinessCheckout);
        vatField.visible(isBusinessCheckout);
    }

    return function(Shipping) {
        return Shipping.extend({
            initialize: function() {
                this._super();

                var subscription = this.elems.subscribe(function(elems) {
                    var lastItem = elems[elems.length-1];
                    if (lastItem.index === 'shipping-address-fieldset') {
                        toggleFields(lastItem);

                        $(".cs-checkout").on("change", "#shipping-new-address-form select", function( event ) {
                            var selectedOption = $("option:selected", this).text(); 
                            if (selectedOption === 'Private') { 
                                isBusinessCheckout = false;
                            } else if (selectedOption === 'Business') {
                                isBusinessCheckout = true;
                            }
                            toggleFields(lastItem)
                        });
                        
                        subscription.dispose();
                    }
                });
            }    
        });
    };
});
