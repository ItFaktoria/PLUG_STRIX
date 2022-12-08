define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        if (window.checkoutConfig.payment.faktoriaPayment.isActive) {
            rendererList.push(
                {
                    type: 'faktoria_payment',
                    component: 'Faktoria_FaktoriaFrontendUi/js/view/payment/method-renderer/faktoria_payment'
                },
            );
        }

        return Component.extend({});
    }
);
