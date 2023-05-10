<?php
/**
 * BP Classic Core Filters.
 *
 * @package bp-classic\inc\core
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Force BuddyPress directories to use the `page` post type.
 *
 * @since 1.0.0
 *
 * @return string The name of the post type to use for BuddyPress directories.
 */
function bp_classic_get_directory_post_type() {
	return 'page';
}
add_filter( 'bp_core_get_directory_post_type', 'bp_classic_get_directory_post_type', 10, 0 );

/**
 * Force BuddyPress to use the Legacy URL parser.
 *
 * @since 1.0.0
 *
 * @return string The name of the Legacy URL parser.
 */
function bp_classic_use_legacy_parser() {
	return 'legacy';
}
add_filter( 'bp_core_get_query_parser', 'bp_classic_use_legacy_parser', 10, 0 );
