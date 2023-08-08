<?php
/**
 * BuddyPress Classic backwards compatibility add-on.
 *
 * @package   bp-classic
 * @author    The BuddyPress Community
 * @license   GPL-2.0+
 * @link      https://buddypress.org
 *
 * @buddypress-plugin
 * Plugin Name:       BP Classic
 * Plugin URI:        https://github.com/buddypress/bp-classic
 * Description:       BuddyPress Classic backwards compatibility add-on.
 * Version:           1.0.0
 * Author:            The BuddyPress Community
 * Author URI:        https://buddypress.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages/
 * Text Domain:       bp-classic
 * GitHub Plugin URI: https://github.com/buddypress/bp-classic
 * Requires at least: 5.8
 * Requires PHP:      5.6
 * Requires Plugins:  buddypress
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Class
 *
 * @since 1.0.0
 */
final class BP_Classic {
	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Used to check if BuddyPress & BP Classic are sharing the same network config.
	 *
	 * @var object
	 */
	protected static $network_config = null;

	/**
	 * Used to store dynamic properties.
	 *
	 * @var array
	 */
	private $data = array();

	/**
	 * Initialize the plugin.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		// Autoload Classes.
		spl_autoload_register( array( $this, 'autoload' ) );

		// Load Globals & Functions.
		$inc_path = plugin_dir_path( __FILE__ ) . 'inc/';

		require $inc_path . 'globals.php';
		require $inc_path . 'functions.php';
		require $inc_path . 'loader.php';
	}

	/**
	 * Class Autoload function
	 *
	 * @since  1.0.0
	 *
	 * @param  string $class The class name.
	 */
	public function autoload( $class ) {
		$name = str_replace( '_', '-', strtolower( $class ) );

		if ( 0 !== strpos( $name, 'bp-classic' ) ) {
			return;
		}

		$name_parts = explode( '-', $name );
		$component  = $name_parts[2];

		$path = plugin_dir_path( __FILE__ ) . "inc/{$component}/classes/class-{$name}.php";

		// Sanity check.
		if ( ! file_exists( $path ) ) {
			return;
		}

		require $path;
	}

	/**
	 * Magic method for checking the existence of a plugin global variable.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Key to check the set status for.
	 * @return bool
	 */
	public function __isset( $key ) {
		return isset( $this->data[ $key ] );
	}

	/**
	 * Magic method for getting a plugin global variable.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Key to return the value for.
	 * @return mixed
	 */
	public function __get( $key ) {
		$retval = null;
		if ( isset( $this->data[ $key ] ) ) {
			$retval = $this->data[ $key ];
		}

		return $retval;
	}

	/**
	 * Magic method for setting a plugin global variable.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key   Key to set a value for.
	 * @param mixed  $value Value to set.
	 */
	public function __set( $key, $value ) {
		$this->data[ $key ] = $value;
	}

	/**
	 * Magic method for unsetting a plugin global variable.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Key to unset a value for.
	 */
	public function __unset( $key ) {
		if ( isset( $this->data[ $key ] ) ) {
			unset( $this->data[ $key ] );
		}
	}

	/**
	 * Checks whether BuddyPress is active.
	 *
	 * @since 1.0.0
	 */
	public static function is_buddypress_supported() {
		// Skip the check when running PHP Unit Tests.
		if ( function_exists( 'tests_add_filter' ) ) {
			return true;
		}

		$bp_plugin_basename      = 'buddypress/bp-loader.php';
		$is_buddypress_supported = false;
		$sitewide_plugins        = (array) get_site_option( 'active_sitewide_plugins', array() );

		if ( $sitewide_plugins ) {
			self::$network_config       = new stdClass();
			$is_buddypress_supported    = isset( $sitewide_plugins[ $bp_plugin_basename ] );

			// Informs about whether BuddyPress is network activated or not.
			self::$network_config->bp_is_network_active = $is_buddypress_supported;

			$bp_classic_plugin_basename = plugin_basename( __FILE__ );

			// Informs about whether BuddyPress is network activated or not.
			self::$network_config->bp_classic_is_network_active = isset( $sitewide_plugins[ $bp_classic_plugin_basename ] );

			// BP & BP Classic need to be activated the same way in Multisite configs.
			if ( self::$network_config->bp_classic_is_network_active !== self::$network_config->bp_is_network_active ) {
				self::$network_config->same = 'no';
				return false;
			} else {
				self::$network_config->same = 'yes';
			}
		}

		if ( ! $is_buddypress_supported ) {
			$plugins                 = (array) get_option( 'active_plugins', array() );
			$is_buddypress_supported = in_array( $bp_plugin_basename, $plugins, true );
		}

		if ( $is_buddypress_supported ) {
			$is_buddypress_supported = version_compare( bp_get_version(), '12.0.0-alpha', '>=' );
		}

		return $is_buddypress_supported;
	}

	/**
	 * Displays an admin notice to explain BP Classic requires BuddyPress 12.0.0.
	 *
	 * @since 1.0.0
	 */
	public static function admin_notice() {
		if ( ! function_exists( 'buddypress' ) || self::is_buddypress_supported() ) {
			return false;
		}

		if ( 'network_admin_notices' === current_action() && empty( self::$network_config->bp_classic_is_network_active ) ) {
			return false;
		}

		$show_notice = get_site_transient( 'bp_classic_show_notice' );

		if ( ! $show_notice ) {
			printf(
				'<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
				sprintf(
					/* translators: %s. is current version of BuddyPress. */
					esc_html__( 'Friendly reminder: BP Classic only runs when BuddyPress version is 12.0.0 (or up). Your current version of BuddyPress is: %s. Unless you activated BP Classic in anticipation of the 12.0.0 upgrade, you can deactivate it. This information will be shown regularly (every week) and as long as your BuddyPress version is lower than 12.0.0.', 'bp-classic' ),
					bp_get_version() // phpcs:ignore
				)
			);

			if ( isset( self::$network_config->same ) && 'no' === self::$network_config->same ) {
				printf(
					'<div class="notice notice-error is-dismissible"><p>%s</p></div>',
					esc_html__( 'Please, make sure to activate BP Classic at the network level, just like BuddyPress is.', 'bp-classic' )
				);
			}

			set_site_transient( 'bp_classic_show_notice', true, WEEK_IN_SECONDS );
		}
	}

	/**
	 * Switch BP Directory pages post type, if needed.
	 *
	 * @since 1.0.0
	 */
	public static function switch_directory_post_type() {
		if ( ! self::is_buddypress_supported() ) {
			return;
		}

		require_once plugin_dir_path( __FILE__ ) . 'inc/migrate.php';

		$post_type = 'page';
		if ( 'page' === bp_core_get_directory_post_type() ) {
			$post_type = 'buddypress';

			bp_classic_restore_default_theme();
		}

		// Run the switcher.
		bp_classic_switch_directory_post_type( $post_type );
	}

	/**
	 * Determine whether BuddyPress should register the `themes` directory.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean True if the `themes` directory should be registered.
	 *                 False otherwise.
	 */
	public static function do_register_theme_directory() {
		$register = false;

		if ( ! self::is_buddypress_supported() ) {
			return $register;
		}

		/*
		 * If bp-default exists in another theme directory, bail.
		 * This ensures that the version of bp-default in the regular themes
		 * directory will always take precedence, as part of a migration away
		 * from the version packaged with BuddyPress.
		 */
		foreach ( array_values( (array) $GLOBALS['wp_theme_directories'] ) as $directory ) {
			if ( is_dir( $directory . '/bp-default' ) ) {
				return $register;
			}
		}

		// If the current theme is bp-default (or a bp-default child), BP should register its directory.
		$register = 'bp-default' === get_stylesheet() || 'bp-default' === get_template();

		// Legacy sites continue to have the theme registered.
		if ( empty( $register ) && ( 1 === (int) get_site_option( '_bp_retain_bp_default' ) ) ) {
			$register = true;
		}

		/**
		 * Filters whether BuddyPress should register the bp-themes directory.
		 *
		 * @since 1.0.0
		 *
		 * @param bool $register If bp-themes should be registered.
		 */
		return apply_filters( 'bp_do_register_theme_directory', $register );
	}

	/**
	 * Set up BuddyPress's legacy theme directory.
	 *
	 * BuddyPress is no more including BP Default. This plugin
	 * is there to provide backward compatibility to BuddyPress
	 * setups still using this deprecated theme.
	 *
	 * @since 1.0.0
	 *
	 * @param BuddyPress $bp The main BuddyPress instance.
	 */
	public static function register_theme_directory( $bp ) {
		if ( ! self::do_register_theme_directory() ) {
			return;
		}

		$bp->old_themes_dir = plugin_dir_path( __FILE__ ) . 'themes';
		$bp->old_themes_url = plugins_url( 'themes', __FILE__ );

		register_theme_directory( $bp->old_themes_dir );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return BP_Classic|false An instance of this class.
	 *                          False if BuddyPress is not supported.
	 */
	public static function start() {
		// This plugin is only usable with BuddyPress.
		if ( ! self::is_buddypress_supported() ) {
			return false;
		}

		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

/**
 * Start Add-on.
 *
 * @since 1.0.0
 *
 * @return BP_Classic|false An instance of this class.
 *                          False if BuddyPress is not supported.
 */
function bp_classic() {
	return BP_Classic::start();
}
add_action( 'bp_loaded', 'bp_classic', -1 );

// Displays a notice to inform BP Classic needs to be activated after BuddyPress.
add_action( 'network_admin_notices', array( 'BP_Classic', 'admin_notice' ) );
add_action( 'admin_notices', array( 'BP_Classic', 'admin_notice' ) );

// Eventually registers the BP Default theme directory.
add_action( 'bp_after_setup_actions', array( 'BP_Classic', 'register_theme_directory' ) );

/*
 * Use Activation and Deactivation to switch directory pages post type between WP pages
 * and BuddyPress one.
 */
register_activation_hook( __FILE__, array( 'BP_Classic', 'switch_directory_post_type' ) );
register_deactivation_hook( __FILE__, array( 'BP_Classic', 'switch_directory_post_type' ) );
