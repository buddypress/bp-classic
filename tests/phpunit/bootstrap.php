<?php
/**
 * PHPUnit bootstrap file
 *
 * @package bp-classic\tests\phpunit
 * @since 1.0.0
 */

// If we're running in WP's build directory, ensure that WP knows that, too.
if ( 'build' === getenv( 'LOCAL_DIR' ) ) {
	define( 'WP_RUN_CORE_TESTS', true );
}

$_tests_dir = null;

// Should we use wp-phpunit?
if ( getenv( 'WP_PHPUNIT__TESTS_CONFIG' ) ) {
	require_once dirname( __FILE__, 3 ) . '/vendor/autoload.php';

	if ( getenv( 'WP_PHPUNIT__DIR' ) ) {
		$_tests_dir = getenv( 'WP_PHPUNIT__DIR' );
	}
}

// Defines WP_TEST_DIR & WP_DEVELOP_DIR if not already defined.
if ( is_null( $_tests_dir ) ) {
	$wp_develop_dir = getenv( 'WP_DEVELOP_DIR' );
	if ( ! $wp_develop_dir ) {
		if ( defined( 'WP_DEVELOP_DIR' ) ) {
			$wp_develop_dir = WP_DEVELOP_DIR;
		} else {
			$wp_develop_dir = dirname( __FILE__, 7 );
		}
	}

	$_tests_dir = $wp_develop_dir . '/tests/phpunit';
}

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
	die( "The WordPress PHPUnit test suite could not be found.\n" );
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

if ( ! defined( 'BP_TESTS_DIR' ) ) {
	$bp_tests_dir = getenv( 'BP_TESTS_DIR' );
	if ( $bp_tests_dir ) {
		define( 'BP_TESTS_DIR', $bp_tests_dir );
	} else {
		define( 'BP_TESTS_DIR', dirname( dirname( __FILE__ ) ) . '/../../buddypress/tests/phpunit' );
	}
}

/**
 * Load the BP Classic plugin.
 *
 * @since 1.0.0
 */
function _load_bp_classic_plugin() {

	// Make sure BP is installed and loaded first.
	require BP_TESTS_DIR . '/includes/loader.php';

	// Load our plugin.
	require_once dirname( __FILE__ ) . '/../../class-bp-classic.php';
}
tests_add_filter( 'muplugins_loaded', '_load_bp_classic_plugin' );

// Start up the WP testing environment.
require_once $_tests_dir . '/includes/bootstrap.php';

// Load the BP test files.
echo "Loading BuddyPress testcase...\n";
require_once BP_TESTS_DIR . '/includes/testcase.php';
