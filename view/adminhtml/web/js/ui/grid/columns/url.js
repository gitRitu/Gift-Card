define([
    'Magento_Ui/js/grid/columns/column'
], function (Column) {
    'use strict';

    return Column.extend({
        defaults: {
            bodyTmpl: 'Dotsquare_Giftcard/ui/grid/columns/cells/url'
        },

        /**
         * Retrieve label for column
         *
         * @returns {String}
         */
        getLabel: function(row) {
            return row[this.index + '_label'];
        },

        /**
         * Retrieve url for column
         *
         * @returns {String}
         */
        getUrl: function(row) {
            return row[this.index + '_url'];
        }
    });
});
