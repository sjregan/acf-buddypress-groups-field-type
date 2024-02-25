<?php
/**
 * Plugin Name: Advanced Custom Fields: Buddypress Groups Field Type
 * Plugin URI: https://github.com/sjregan/acf-field-buddypress-groups
 * Description: BuddyPress Groups field for Advanced Custom Fields.
 * Version: 1.0.0
 * Author: SJ Regan
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

load_plugin_textdomain( 'acf_buddypress_groups_field', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

/**
 * Init.
 *
 * @since 1.0.0
 */
function acf_bp_groups_ft_init() {
    if ( ! class_exists( '\acf_field' ) ) {
        return;
    }

    require_once( 'class-acf-field-select-bp-groups.php' );
    acf_register_field_type( 'acf_field_select_bp_groups' );
}

add_action( 'acf/include_field_types', 'acf_bp_groups_ft_init', 10, 0 );
