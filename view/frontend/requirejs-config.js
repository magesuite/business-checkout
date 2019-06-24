var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/set-shipping-information': {
                'MageSuite_BusinessCheckout/js/action/set-shipping-information-mixin': true
            },
            'Magento_Checkout/js/view/shipping': {
                'MageSuite_BusinessCheckout/js/shipping-fields-toggle-mixin': true
            }
        }
    }
};