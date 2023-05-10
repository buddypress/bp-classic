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
		require $path . '/core/admin/functions.php';
		require $path . '/core/admin/slugs.php';

		// Members is always active.
		require $path . '/members/admin/functions.php';
	}

	require $path . '/core/filters.php';

	if ( bp_is_active( 'activity' ) ) {
		if ( is_admin() ) {
			require $path . '/activity/admin/functions.php';
		}
	}

	if ( bp_is_active( 'blogs' ) ) {
		if ( is_admin() ) {
			require $path . '/blogs/admin/functions.php';
		}
	}

	if ( bp_is_active( 'groups' ) ) {
		if ( is_admin() ) {
			require $path . '/groups/admin/functions.php';
		}
	}
}
add_action( '_bp_classic_includes', 'bp_classic_includes', 1, 1 );
