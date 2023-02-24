<?php
/**
 * BP Classic Admin Funcions.
 *
 * @package bp-classic\inc
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Code to reorganize.

function bp_classic_admin_menus( $submenu_pages = array() ) {
	$settings_page = bp_core_do_network_admin() ? 'settings.php' : 'options-general.php';
	$capability    = bp_core_do_network_admin() ? 'manage_network_options' : 'manage_options';

	$bp_page_settings_page = add_submenu_page(
		$settings_page,
		__( 'BuddyPress Pages', 'bp-classic' ),
		__( 'BuddyPress Pages', 'bp-classic' ),
		$capability,
		'bp-page-settings',
		'bp_core_admin_slugs_settings'
	);

	$submenu_pages['settings']['bp-page-settings'] = $bp_page_settings_page;
	add_action( "admin_head-{$bp_page_settings_page}", 'bp_core_modify_admin_menu_highlight' );
}
add_action( 'bp_admin_submenu_pages', 'bp_classic_admin_menus', 10, 1 );

function bp_classic_admin_head() {
	$settings_page = bp_core_do_network_admin() ? 'settings.php' : 'options-general.php';
	remove_submenu_page( $settings_page, 'bp-page-settings' );
}
add_action( 'bp_admin_head', 'bp_classic_admin_head', 1001 );

function bp_classic_admin_settings_tabs( $settings_tabs = array() ) {
	$settings_tabs['1'] = array(
		'id'   => 'bp-page-settings',
		'href' => bp_get_admin_url( add_query_arg( array( 'page' => 'bp-page-settings' ), 'admin.php' ) ),
		'name' => __( 'Pages', 'bp-classic' ),
	);

	return $settings_tabs;
}
add_filter( 'bp_core_get_admin_settings_tabs', 'bp_classic_admin_settings_tabs', 10, 1 );
