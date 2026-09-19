<?php
/**
 * Plugin Name:       Filter WC Orders
 * Plugin URI:        https://dineshinaublog.wordpress.com/filter-wc-orders/
 * Description:       It helps in sorting woocommerce orders based on a payment gateway.
 * Version:           1.0.4
 * Author:            Dinesh Yadav
 * Author URI:        https://dineshinaublog.wordpress.com
 * Text Domain:       filter-wc-orders
 * Domain Path:       /languages
 *
 * Requires at least: 6.5
 * Tested up to: 7.1
 * Requires PHP: 7.4
 *
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package filter-wc-orders
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'DKFWCO_Core' ) ) {
	/**
	 * Core class of the plugin.
	 *
	 * Class DKFWCO_Core
	 */
	class DKFWCO_Core {
		/**
		 * Instance variable of this class.
		 *
		 * @var DKFWCO_Core
		 */
		public static $_instance = null;

		/**
		 *  Admin instance for this plugin.
		 *
		 * @var DKFWCO_Admin
		 */
		public $admin;

		/**
		 * DKFWCO_Core constructor.
		 */
		public function __construct() {
			/**
			 * Load important variables and constants.
			 */
			$this->define_plugin_properties();

			/**
			 * Initiates and load hooks.
			 */
			$this->load_hooks();
		}

		/**
		 * Defining constants.
		 */
		public function define_plugin_properties() {
			define( 'DKFWCO_VERSION', '1.0.4' );
			define( 'DKFWCO_PLUGIN_FILE', __FILE__ );
			define( 'DKFWCO_PLUGIN_DIR', __DIR__ );
			define( 'DKFWCO_PLUGIN_SLUG', 'filter-wc-orders' );
			add_action( 'plugins_loaded', array( $this, 'load_wp_dependent_properties' ), 1 );
		}

		/**
		 * Defining WP dependent properties.
		 */
		public function load_wp_dependent_properties() {
			define( 'DKFWCO_PLUGIN_URL', untrailingslashit( plugin_dir_url( DKFWCO_PLUGIN_FILE ) ) );
			define( 'DKFWCO_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		}

		/**
		 * Loading hooks.
		 */
		public function load_hooks() {
			/**
			 * Initialize Localization.
			 */
			add_action( 'init', array( $this, 'localization' ) );
			add_action( 'plugins_loaded', array( $this, 'load_classes' ), 1 );
		}

		/**
		 * Localizing the plugin text strings.
		 */
		public function localization() {
			load_plugin_textdomain( 'filter-wc-orders', false, __DIR__ . '/languages/' );
		}

		/**
		 * Loading plugin classes.
		 */
		public function load_classes() {
			/**
			 * Loads the Admin file.
			 */
			require __DIR__ . '/admin/class-dkfwco-admin.php';
			$this->admin = DKFWCO_Admin::get_instance();
		}

		/**
		 * Function to provide instance of this class.
		 *
		 * @return DKFWCO_Core|null
		 */
		public static function get_instance() {
			if ( null === self::$_instance ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}
	}
}

if ( ! function_exists( 'dkwc_log' ) ) {
	function dkwc_log( $message, $level, $context = array() ) {
		$source            = ( is_array( $context ) && ! empty( $context['source'] ) ) ? $context['source'] : 'dkwc';
		$context['source'] = $source;
		$logger            = wc_get_logger();
		$current_user_id   = get_current_user_id();

		$in_action = wp_sprintf( ( /* translators: %s current user id */ esc_html__( 'User in action: %s: ', 'dkwc' ) ), $current_user_id );
		$message   = $in_action . $message;

		$logger->log( $level, $message, $context );
	}
}

if ( ! function_exists( 'dkfwco_core' ) ) {
	/**
	 * Returning Filter Order Core class.
	 *
	 * @return DKFWCO_Core
	 */
	function dkfwco_core() {
		return DKFWCO_Core::get_instance();
	}
}

dkfwco_core();
