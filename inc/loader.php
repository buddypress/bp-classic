<?php
/**
 * BP Classic Loader.
 *
 * @package bp-classic\inc
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Loader function.
 *
 * @since 1.0.0
 *
 * @param string $plugin_dir The plugin root directory.
 */
function bp_classic_includes( $plugin_dir = '' ) {
	$path = trailingslashit( $plugin_dir );

	// Core is always required.
	require $path . '/core/functions.php';

	if ( is_admin() ) {
		require $path . '/core/admin/slugs.php';
	}
}
add_action( '_bp_classic_includes', 'bp_classic_includes', 1, 1 );
