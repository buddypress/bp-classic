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
	$inc_dir  = plugin_dir_path( __FILE__ );
	$bpc->dir = $inc_dir;

	// URL.
	$plugin_url = plugins_url( '', dirname( __FILE__ ) );
	$bpc->url   = $plugin_url;

	/**
	 * Private (do not use) hook used to include files early.
	 *
	 * @since 1.0.0
	 *
	 * @param string $plugin_dir The plugin root directory.
	 */
	do_action( '_bp_classic_includes', $inc_dir );
}
add_action( 'bp_loaded', 'bp_classic_globals', 1 );
