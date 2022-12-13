define(
    [
        'Magento_Checkout/js/view/payment/default',
        'Magento_Checkout/js/action/redirect-on-success',
        'mage/url'
    ],
    function (Component, redirectOnSuccessAction, url) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Spingo_SpingoFrontendUi/spingo_payment'
            },
            afterPlaceOrder: function () {
                redirectOnSuccessAction.redirectUrl = url.build('spingo/form');
                this.redirectAfterPlaceOrder = true;
            }
        });
    }
);
