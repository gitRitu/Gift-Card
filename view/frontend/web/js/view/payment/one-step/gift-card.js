define(
    [
        'jquery',
        'Dotsquare_Giftcard/js/view/payment/giftcard',
        'Dotsquare_Giftcard/js/action/apply-giftcard-code',
        'Dotsquare_Giftcard/js/view/payment/one-step/apply-by-code-flag',
        'Dotsquare_Giftcard/js/model/payment/giftcard-messages',
        'Dotsquare_OneStepCheckout/js/model/payment-option/message-processor'
    ],
    function (
        $,
        Component,
        applyGiftCardCodeAction,
        applyByCodeFlag,
        messageContainer,
        messageProcessor
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Dotsquare_Giftcard/payment/one-step/gift-card',
                inputSelector: '#giftcard_code'
            },

            /**
             * @inheritdoc
             */
            apply: function() {
                var self = this,
                    input = $(this.inputSelector);

                if (this.validate()) {
                    applyByCodeFlag(false);
                    applyGiftCardCodeAction(this.giftcardCode())
                        .done(function () {
                            self.giftcardCode('');
                            messageProcessor.processSuccess(input, messageContainer)
                        })
                        .fail(function () {
                            messageProcessor.processError(input, messageContainer)
                        });
                }
            },

            /**
             * @inheritdoc
             */
            applyByCode: function(giftcardCode) {
                applyByCodeFlag(true);
                applyGiftCardCodeAction(giftcardCode);
            },

            /**
             * @inheritdoc
             */
            validate: function() {
                messageProcessor.resetImmediate($(this.inputSelector));

                return this._super();
            }
        });
    }
);
