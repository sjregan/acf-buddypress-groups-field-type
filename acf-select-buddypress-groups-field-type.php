<?php
/**
 * Plugin Name: Advanced Custom Fields: Buddypress Groups Field Type
 * Plugin URI: https://github.com/sjregan/acf-field-buddypress-groups
 * Description: BuddyPress Groups field for Advanced Custom Fields.
 * Version: 1.1.0
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

/**
 * Register scripts.
 *
 * @since 1.1.0
 *
 * @param string $version ACF version.
 * @param string $suffix Filename suffix.
 */
function acf_bp_groups_ft_register_scripts( string $version, string $suffix ) {
    $plugin_url = plugin_dir_url( __FILE__ );

    wp_register_script(
        'acf-bp-groups-ft-input',
        $plugin_url . 'assets/input' . $suffix . '.js',
        [ 'acf-input' ],
        '1.0.0'
    );
}

add_action( 'acf/register_scripts', 'acf_bp_groups_ft_register_scripts', 10, 2 );

/**
 * Enqueue scripts.
 *
 * @since 1.1.0
 */
function acf_bp_groups_ft_enqueue_scripts() {
    $wp_scripts = wp_scripts();

    if ( ! in_array( 'acf-input', $wp_scripts->queue ) ) {
        return;
    }

    wp_enqueue_script( 'acf-bp-groups-ft-input' );
}

add_action('acf/input/admin_enqueue_scripts', 'acf_bp_groups_ft_enqueue_scripts');
