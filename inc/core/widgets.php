<?php
/**
 * BP Classic Core Widget Functions.
 *
 * @package bp-classic\inc\core
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the Login widget.
 *
 * @since 1.0.0
 */
function bp_classic_register_login_widget() {
	register_widget( 'BP_Classic_Core_Login_Widget' );
}

/**
 * Register bp-core widgets.
 *
 * @since 1.0.0
 */
function bp_classic_register_widgets() {
	add_action( 'widgets_init', 'bp_classic_register_login_widget' );
}
add_action( 'bp_register_widgets', 'bp_classic_register_widgets' );
