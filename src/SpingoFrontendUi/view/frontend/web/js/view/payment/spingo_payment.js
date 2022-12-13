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
        if (window.checkoutConfig.payment.spingoPayment.isActive) {
            rendererList.push(
                {
                    type: 'spingo_payment',
                    component: 'Spingo_SpingoFrontendUi/js/view/payment/method-renderer/spingo_payment'
                },
            );
        }

        return Component.extend({});
    }
);
