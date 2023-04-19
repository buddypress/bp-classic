<?php
/**
 * BP Classic Admin functions.
 *
 * @package bp-classic\inc\core\admin
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add notice if no rewrite rules are enabled.
 *
 * @since 1.0.0
 */
function bp_classic_admin_permalink_notice() {
	if ( ! bp_has_pretty_urls() ) {
		bp_core_add_admin_notice(
			sprintf(
				// Translators: %s is the url to the permalink settings.
				__( '<strong>BuddyPress is almost ready</strong>. You must <a href="%s">update your permalink structure</a> to something other than the default for it to work.', 'bp-classic' ),
				admin_url( 'options-permalink.php' )
			),
			'error'
		);
	}
}
add_action( 'bp_admin_init', 'bp_classic_admin_permalink_notice', 1010 );
