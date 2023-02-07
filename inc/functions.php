<?php
/**
 * BP Classic Funcions.
 *
 * @package bp-classic\inc
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load translation.
 *
 * @since 1.0.0
 */
function bp_classic_load_translation() {
	$bpc = bp_classic();

	// Load translations.
	load_plugin_textdomain( 'bp-classic', false, trailingslashit( basename( $bpc->dir ) ) . 'languages' );
}
add_action( 'bp_loaded', 'bp_classic_load_translation' );
