<?php
/**
 * Custom BP Groups select field class.
 *
 * @since 1.0.0
 */
class acf_field_select_bp_groups extends acf_field_select {
    /**
     * Initialisation.
     *
     * @since 1.0.0
     */
    public function initialize() {
        $this->name          = 'select_bp_groups';
        $this->label         = _x( 'BuddyPress Groups', 'select field label', 'acf-bp-groups-field-type' );
        $this->category      = 'relational';
        $this->description   = __( 'A dropdown list with a selection of choices that you specify.', 'acf' );
        $this->preview_image = acf_get_url() . '/assets/images/field-type-previews/field-preview-select.png';
        $this->defaults      = [
            'groups'             => 0,
            'multiple'           => 1,
            'allow_null'         => 0,
            'choices'            => [],
            'default_value'      => '',
            'ui'                 => 0,
            'ajax'               => 0,
            'placeholder'        => '',
            'return_format'      => 'id',
            'display_all_groups' => 1,
        ];

        // AJAX
        add_action( 'wp_ajax_acf/fields/select_bp_groups/query', [ $this, 'ajax_query' ] );
        add_action( 'wp_ajax_nopriv_acf/fields/select_bp_groups/query', [ $this, 'ajax_query' ] );
    }

    /**
     * Add additional field settings.
     *
     * @since 1.0.0
     * @param array $field
     */
    public function render_field_settings( $field ) {
        acf_render_field_setting(
            $field,
            [
                'label'   => _x( 'Display Groups', 'field setting', 'acf-bp-groups-field-type' ),
                'type'    => 'radio',
                'name'    => 'display_all_groups',
                'choices' => [
                    1 => _x( 'All Groups', 'field setting choice', 'acf-bp-groups-field-type' ),
                    0 => _x( 'Member Groups', 'field setting choice', 'acf-bp-groups-field-type' ),
                ],
                'layout'  => 'horizontal',
            ]
        );

        acf_render_field_setting(
            $field,
            array(
                'label'        => __( 'Select Multiple', 'acf' ),
                'instructions' => 'Allow content editors to select multiple values',
                'name'         => 'multiple',
                'type'         => 'true_false',
                'ui'           => 1,
            )
        );
    }

    /**
     * Render field.
     *
     * @since 1.0.0
     * @param array $field
     */
    public function render_field( $field ) {
        $choices = [];

        if ( $field['display_all_groups'] ) {
            $choices[0] = _x( 'None', 'field value choice', 'acf-bp-groups-field-type' );
        }

        if ( function_exists( 'bp_is_active' ) && bp_is_active( 'groups' ) ) {
            $user_id = ( $field['display_all_groups'] === 1 ) ? false : get_current_user_id();
            $args    = [
                'per_page'    => -1,
                'user_id'     => $user_id,
                'show_hidden' => true,
            ];

            $groups  = groups_get_groups( $args );

            foreach ( $groups['groups'] as $group ) {
                $choices[ $group->id ] = bp_get_group_name( $group );
            }
        }

        $field['choices'] = $choices;

        parent::render_field( $field );
    }

    /**
     * Format field value.
     *
     * @since 1.0.0
     * @param mixed $value
     * @param int   $post_id
     * @param array $field
     * @return array|mixed|null
     */
    public function format_value( $value, $post_id, $field ) {
        $value = parent::format_value( $value, $post_id, $field );

        if ( ! $field['multiple'] ) {
            $value = array_shift( $value );
        }

        return $value;
    }

    /**
     * Ensure the value is either null, or an array of integers.
     *
     * @since 1.0.0
     * @param mixed $value
     * @param int $post_id
     * @param array $field
     * @return array|null
     */
    function update_value( $value, $post_id, $field ): ?array {
        // Bail early if no value
        if ( empty( $value ) ) {
            return null;
        }

        $value = parent::update_value( $value, $post_id, $field );

        if ( ! is_array( $value ) ) {
            return null;
        }

        $value = array_values( array_filter( array_map( 'intval', $value ) ) );

        if ( count( $value ) === 0 ) {
            return null;
        }

        return $value;
    }
}
