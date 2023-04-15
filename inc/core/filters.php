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
 * Bypass BuddyPress 12.0+ slug customization to preserve the classic experience.
 *
 * @since 1.0.0
 *
 * @param string $value        An empty string.
 * @param string $default_slug The screen default slug, used by BuddyPress as a fallback.
 * @return string              The default slug.
 */
function bp_classic_use_default_slug( $slug = '', $default_slug = '' ) {
	return $default_slug;
}
add_filter( 'bp_rewrites_pre_get_slug', 'bp_classic_use_default_slug', 10, 2 );
