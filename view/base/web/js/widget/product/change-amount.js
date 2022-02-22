
define([
    'jquery'
], function($){
    'use strict';

    $.widget('mage.dsGiftCardChangeAmount', {
        options: {
            customAmountSelector: '#ds-gc-custom-amount-box'
        },

        /**
         * Initialize widget
         */
        _create: function() {
            this.element.on('change', $.proxy(this.changeAmount, this)).trigger('change');
        },

        /**
         * Change Gift Card amount value
         * @private
         */
        changeAmount: function() {
            if (this.element.val() === 'custom') {
                $(this.options.customAmountSelector).show();
                $(this.options.customAmountSelector).find('input, select, textarea').prop('disabled', false);
            } else {
                $(this.options.customAmountSelector).hide();
                $(this.options.customAmountSelector).find('input, select, textarea').prop('disabled', true);
            }
        }
    });

    return $.mage.dsGiftCardChangeAmount;
});
