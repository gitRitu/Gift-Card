define([
    'ko',
    'Magento_Ui/js/view/messages',
    'Dotsquare_Giftcard/js/model/payment/giftcard-messages',
    'Dotsquare_Giftcard/js/view/payment/one-step/apply-by-code-flag'
], function (ko, Component, messageContainer, applyByCodeFlag) {
    'use strict';

    return Component.extend({

        /**
         * @inheritdoc
         */
        initialize: function (config) {
            return this._super(config, messageContainer);
        },

        /**
         * @inheritdoc
         */
        isVisible: function () {
            this.isHidden(this.messageContainer.hasMessages());

            return applyByCodeFlag();
        }
    });
});
