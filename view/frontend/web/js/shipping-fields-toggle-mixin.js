define(['uiRegistry'], function (registry) {
    'use strict';

    var isEnabled = Boolean(
        window.checkoutConfig.business_checkout_config === '1'
    );

    return function (Shipping) {
        return isEnabled
            ? Shipping.extend({
                  initialize: function () {
                      this._super();

                      var fieldsetName =
                          'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset';
                      var businessOnlyFields = ['company', 'vat_id'];
                      var toggleFields = function (fieldValue) {
                          var isBusinessCheckout = fieldValue === 'business';

                          businessOnlyFields.forEach(function (fieldName) {
                              registry.get(
                                  fieldsetName + '.' + fieldName,
                                  function (field) {
                                      field.visible(isBusinessCheckout);
                                  }
                              );
                          });
                      };

                      registry.get(fieldsetName + '.customer_type', function (
                          customerType
                      ) {
                          toggleFields(customerType.value());
                          customerType.value.subscribe(function (value) {
                              toggleFields(value);
                          });
                      });
                  },
              })
            : Shipping;
    };
});
