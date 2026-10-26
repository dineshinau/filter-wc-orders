<?php
/**
 * Plugin Name: Filter WC Orders
 * Plugin URI: https://dineshinaublog.wordpress.com/filter-wc-orders/
 * Description: It helps in sorting woocommerce orders based on a payment gateway.
 * Version: 1.1.0
 * Author: Dinesh Yadav
 * Author URI: https://github.com/dineshinau
 * Text Domain: filter-wc-orders
 * Domain Path: /languages
 * License: GPL v3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * Requires at least: 6.5
 * Requires PHP: 7.4
 * WC requires at least: 7.0
 * WC tested up to: 11.2
 * Requires Plugins: woocommerce
 *
 * Filter WC Orders is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Filter WC Orders is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Bulk COGS Editor for WooCommerce. If not, see <http://www.gnu.org/licenses/>.
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
		public static $inst = null;

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
			define( 'DKFWCO_VERSION', '1.1.0' );
			define( 'DKFWCO_PLUGIN_FILE', __FILE__ );
			define( 'DKFWCO_PLUGIN_DIR', __DIR__ );
			define( 'DKFWCO_PLUGIN_SLUG', 'filter-wc-orders' );
			add_action( 'plugins_loaded', array( $this, 'load_wp_dependent_properties' ), 1 );
			add_action( 'before_woocommerce_init', array( $this, 'declare_hpos_compatibility' ) );
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
			add_action( 'plugins_loaded', array( $this, 'load_classes' ), 1 );
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
		 * Declare compatibility with WooCommerce HPOS (High-Performance Order Storage).
		 *
		 * @return void
		 */
		public function declare_hpos_compatibility(): void {
			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
					'custom_order_tables',
					DKFWCO_PLUGIN_FILE,
					true
				);
			}
		}


		/**
		 * Function to provide instance of this class.
		 *
		 * @return DKFWCO_Core|null
		 */
		public static function get_instance() {
			if ( null === self::$inst ) {
				self::$inst = new self();
			}

			return self::$inst;
		}
	}
}

if ( ! function_exists( 'dkfwco_log' ) ) {
	/**
	 * Logging function for the plugin.
	 *
	 * @param string $message Message to log.
	 *
	 * @return void
	 */
	function dkfwco_log( $message ) {
		if ( function_exists( 'wc_get_logger' ) ) {
			$log_enabled = apply_filters( 'dkfwco_is_log_enabled', true );

			if ( $log_enabled ) {
				$logger  = wc_get_logger();
				$done_by = wp_sprintf( ( /* translators: %s current user id */ esc_html__( 'Done by user ID: %s', 'filter-wc-orders' ) ), get_current_user_id() );
				$message = $done_by . ' -> ' . $message;

				$logger->log( 'info', $message, array( 'source' => 'dkfwco' ) );
			}
		}
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
