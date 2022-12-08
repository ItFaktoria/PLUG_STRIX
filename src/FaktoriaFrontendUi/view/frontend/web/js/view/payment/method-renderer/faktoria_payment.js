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
                template: 'Faktoria_FaktoriaFrontendUi/faktoria_payment'
            },
            afterPlaceOrder: function () {
                redirectOnSuccessAction.redirectUrl = url.build('faktoria/form');
                this.redirectAfterPlaceOrder = true;
            }
        });
    }
);
