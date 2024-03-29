
define([
    'Dotsquare_Giftcard/js/model/resource-url-manager',
    'Magento_Checkout/js/model/error-processor',
    'Dotsquare_Giftcard/js/model/payment/giftcard-messages',
    'mage/storage',
    'Magento_Customer/js/model/customer',
    'Dotsquare_Giftcard/js/model/customer/giftcard'
], function (
    urlManager,
    errorProcessor,
    messageContainer,
    storage,
    customer,
    customerGiftcard
) {
    'use strict';
    return function () {
        if (customer.isLoggedIn()) {
            var url = urlManager.getCustomerGiftcardsUrl();

            customerGiftcard.isLoading(true);
            return storage.get(
                url,
                {},
                false
            ).done(
                function (response) {
                    if (response) {
                        customerGiftcard.giftcardCodes(response);
                    }
                }
            ).fail(
                function (response) {
                    errorProcessor.process(response, messageContainer);
                }
            ).always(
                function() {
                    customerGiftcard.isLoading(false);
                }
            );
        }
    };
});
