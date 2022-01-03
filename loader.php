<?php
/**
 * Plugin Name:     Password Protected Add-A-Message
 * Description:     Add a custom message above the password form created by the plugin "Password Protected".
 * Author:          dcavins
 * Version:         1.0.0
 *
 * @package         Password_Protected_Add_Message
 */
namespace Password_Protected_Add_Message;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Add a new "message" option to the Password Protected settings screen.
 *
 * @since   1.0.0
 *
 * @param array $a    Parsed shortcode attributes.
 * @param array $atts Raw shortcode attributes as passed in.
 *
 * @return array $a    Parsed shortcode attributes.
 */
function setup_extra_settings() {
    $options_group = 'password-protected';
    add_settings_field(
        'password_protected_message',
        __( 'Message to display above the password form', 'password-protected' ),
        __NAMESPACE__ . '\\password_protected_message_input',
        $options_group,
        'password_protected'
    );
    register_setting( $options_group, 'password_protected_message', 'wp_filter_post_kses' );
}
add_action( 'admin_init', __NAMESPACE__ . '\\setup_extra_settings' );

/**
 * Output the form element to collect the message input.
 *
 * @since   1.0.0
 *
 * @return string HTML to display.
 */
function password_protected_message_input() {
    $settings = array(
        'textarea_rows' => 5,
    );
    $saved_option = wp_unslash( get_option( 'password_protected_message' ) );
    wp_editor( $saved_option, 'password_protected_message', $settings );
}

/**
 * Add a message above the "password protected" form.
 *
 * @since   1.0.0
 *
 * @return string HTML to display.
 */
function display_message() {
    $message = get_option( 'password_protected_message' );
    if ( $message ) {
        echo apply_filters( 'the_content', wp_unslash( $message ) );
    }
}
add_action( 'password_protected_before_login_form', __NAMESPACE__ . '\\display_message' );
