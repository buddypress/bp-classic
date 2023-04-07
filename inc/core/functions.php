<?php
/**
 * BP Classic Globals.
 *
 * @package bp-classic\inc\core
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add support for a top-level ("root") component.
 *
 * This function originally (pre-1.5) let plugins add support for pages in the
 * root of the install. These root level pages are now handled by actual
 * WordPress pages and this function is now a convenience for compatibility
 * with the new method.
 *
 * @since 1.0.0
 *
 * @param string $slug The slug of the component being added to the root list.
 */
function bp_core_add_root_component( $slug ) {
	$bp = buddypress();

	if ( empty( $bp->pages ) ) {
		$bp->pages = bp_core_get_directory_pages();
	}

	$match = false;

	// Check if the slug is registered in the $bp->pages global.
	foreach ( (array) $bp->pages as $key => $page ) {
		if ( $key == $slug || $page->slug == $slug ) {
			$match = true;
		}
	}

	// Maybe create the add_root array.
	if ( empty( $bp->add_root ) ) {
		$bp->add_root = array();
	}

	// If there was no match, add a page for this root component.
	if ( empty( $match ) ) {
		$add_root_items   = $bp->add_root;
		$add_root_items[] = $slug;
		$bp->add_root     = $add_root_items;
	}

	// Make sure that this component is registered as requiring a top-level directory.
	if ( isset( $bp->{$slug} ) ) {
		$bp->loaded_components[$bp->{$slug}->slug] = $bp->{$slug}->id;
		$bp->{$slug}->has_directory = true;
	}
}

/**
 * Return the domain for the root blog.
 *
 * Eg: http://example.com OR https://example.com
 *
 * @since 1.0.0
 *
 * @return string The domain URL for the blog.
 */
function bp_core_get_root_domain() {
	$domain = bp_rewrites_get_root_url();

	/**
	 * Filters the domain for the root blog.
	 *
	 * @since 1.0.0
	 *
	 * @param string $domain The domain URL for the blog.
	 */
	return apply_filters( 'bp_core_get_root_domain', $domain );
}

/**
 * Return the "root domain", the URL of the BP root blog.
 *
 * @since 1.0.0
 *
 * @return string URL of the BP root blog.
 */
function bp_get_root_domain() {
	$domain = bp_get_root_url();

	/**
	 *  Filters the "root domain", the URL of the BP root blog.
	 *
	 * @since 1.0.0
	 *
	 * @param string $domain URL of the BP root blog.
	 */
	return apply_filters( 'bp_get_root_domain', $domain );
}

/**
 * Output the "root domain", the URL of the BP root blog.
 *
 * @since 1.0.0
 */
function bp_root_domain() {
	bp_root_url();
}
