/**
 * Handles field initialisation, duplication and removal.
 *
 * @since 1.1.0
 */
(function ($) {

    /**
     * Initialise the field.
     *
     * @since 1.1.0
     *
     * @param {{
     *     $el: jQuery,
     *     cid: string,
     *     data: {
     *         key: string,
     *         name: string,
     *         type: string
     *     }
     * }} field ACF field details.
     */
    function initialiseField(field) {
        let customData = field.$el.data('acf-select-bp-groups');

        // Ensure field is not already initialised
        if (customData !== undefined) {
            return;
        }

        // Get the select element
        const $select = field.$el.find('select');

        // Inherit data from select
        $.extend(field.data, $select.data());

        customData = {
            select2: null,
            $select: $select
        };

        // Initialise select2 if required
        if (field.data.ui) {
            // populate ajax_data (allowing custom attribute to already exist)
            let ajaxAction = field.data.ajax_action;

            if (!ajaxAction) {
                ajaxAction = 'acf/fields/select_bp_groups/query';
            }

            customData.select2 = acf.newSelect2(customData.$select, {
                field: field,
                ajax: field.data.ajax,
                multiple: field.data.multiple,
                placeholder: field.data.placeholder,
                allowNull: field.data.allow_null,
                ajaxAction: ajaxAction
            });
        }

        // Save our custom data to the field
        field.$el.data('acf-select-bp-groups', customData);
    }

    /**
     * Destroy the field.
     *
     * @since 1.1.0
     *
     * param {{
     *     $el: jQuery,
     *     cid: string,
     *     data: {
     *         key: string,
     *         name: string,
     *         type: string
     *     }
     * }} field ACF field details.
     */
    function destroyField(field) {
        let customData = field.$el.data('acf-select-bp-groups');

        // Ensure field is initialised
        if (customData === undefined) {
            return;
        }

        if (customData.select2) {
            customData.select2.destroy();
        }
    }

    if (typeof acf.addAction !== 'undefined') {
        /**
         * Run initialiseField when existing fields of this type load,
         * or when new fields are appended via repeaters or similar,
         * or when the field is removed.
         */
        acf.addAction('ready_field/type=select_bp_groups', initialiseField);
        acf.addAction('append_field/type=select_bp_groups', initialiseField);
        acf.addAction('remove_field/type=select_bp_groups', destroyField);
    }

})(jQuery);
