define([
    'jquery'
], function($){
    'use strict';

    $.widget('mage.dsGiftCardChangeDesign', {
        options: {
            templateValueSelector: '#ds_gc_template',
            value: ''
        },

        /**
         * Initialize widget
         */
        _create: function() {
            this.element.on('click', $.proxy(this.onClick, this));
        },

        /**
         * Click on element
         * @private
         */
        onClick: function() {
            this.element.siblings('.ds-gc-product-form-options__template-option').removeClass('selected');
            this.element.addClass('selected');
            $(this.options.templateValueSelector).val(this.options.value).trigger('change');
        }
    });

    return $.mage.dsGiftCardChangeDesign;
});
