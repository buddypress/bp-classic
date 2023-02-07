<?php
/**
 * BP Classic Globals.
 *
 * @package bp-classic\inc
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register plugin globals.
 *
 * @since 1.0.0
 */
function bp_classic_globals() {
	$bpc = bp_classic();

	$bpc->version = '1.0.0-alpha';

	// Path.
	$plugin_dir = plugin_dir_path( dirname( __FILE__ ) );
	$bpc->dir   = $plugin_dir;

	// URL.
	$plugin_url = plugins_url( '', dirname( __FILE__ ) );
	$bpc->url   = $plugin_url;
}
add_action( 'bp_loaded', 'bp_classic_globals', 1 );
