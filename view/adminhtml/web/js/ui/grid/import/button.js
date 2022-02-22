define([
    'uiCollection',
    'uiRegistry'
], function (Collection, registry) {
    'use strict';

    return Collection.extend({
        defaults: {
            template: 'Dotsquare_Giftcard/ui/grid/import',
            displayArea: 'dataGridActions'
        },

        /**
         * Apply action on target component
         */
        applyAction: function () {
            var targetName = this.action.targetName,
                params = this.action.params || [],
                actionName = this.action.actionName,
                target;

            if (registry.has(targetName)) {
                target = registry.async(targetName);

                if (target && typeof target === 'function' && actionName) {
                    params.unshift(actionName);
                    target.apply(target, params);
                }
            }
        }
    });
});
