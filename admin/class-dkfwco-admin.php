<?php
/**
 * Admin file.
 *
 * @package filter-wc-orders
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The admin functionality of the FWCO.
 *
 * Class DKFWCO_Admin
 *
 * @package filter-wc-orders
 */
class DKFWCO_Admin {
	/**
	 * Instance variable of this class.
	 *
	 * @var object $ins Instance.
	 */
	private static $ins = null;

	/**
	 * Initialize the class and set its properties.
	 *
	 * DKFWCO_Admin constructor.
	 */
	public function __construct() {
		add_action( 'restrict_manage_posts', array( $this, 'filter_wc_orders' ), 11 );
		add_action( 'woocommerce_order_list_table_restrict_manage_orders', array( $this, 'filter_wc_orders' ), 11 );
		add_action( 'pre_get_posts', array( $this, 'filter_pre_get_post_query' ) );
		add_filter( 'woocommerce_shop_order_list_table_prepare_items_query_args', array( $this, 'filter_wc_order_queries' ) );
	}

	/**
	 * Creating a new instance of this class.
	 *
	 * @return DKFWCO_Admin|null
	 */
	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	/**
	 * Adding custom order filter dropdown.
	 */
	public function filter_wc_orders() {
		global $typenow;
		$page_name = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
				
		if ( 'shop_order' === $typenow || 'wc-orders' === $page_name ) {
			// All gateway installed on the site.
			$installed_gateways = WC()->payment_gateways->payment_gateways();
			$selected_filter    = wc_clean( filter_input( INPUT_GET, 'dkfwco_filters', FILTER_SANITIZE_FULL_SPECIAL_CHARS ) );
			$filter_value       = '';
			if ( ! empty( $selected_filter ) ) {
				$filter_arr   = explode( '_', $selected_filter );
				$filter_value = count( $filter_arr ) > 2 ? $filter_arr[2] : '';
			} ?>
			<select name="dkfwco_filters" id="dkfwco_filters">
				<option value="">
					<?php esc_html_e( 'No filter', 'filter-wc-orders' ); ?>
				</option>
				<optgroup label="<?php esc_attr_e( 'By Payment Method', 'filter-wc-orders' ); ?>">
					<?php foreach ( $installed_gateways as $gateway_id => $gateway_obj ) : ?>
						<option value="dkfwco_payment_<?php echo esc_attr( $gateway_id ); ?>" <?php echo empty( $filter_value ) ? '' : selected( $gateway_id, $filter_value, false ); ?>>
							<?php echo esc_html( $gateway_obj->get_method_title() ); ?>
						</option>
					<?php endforeach; ?>
				</optgroup>
				<optgroup label="<?php esc_attr_e( 'By User Types', 'filter-wc-orders' ); ?>">
					<option value="dkfwco_users_guest" <?php echo selected( 'guest', $filter_value, false ); ?>><?php esc_html_e( 'Guest Users', 'filter-wc-orders' ); ?></option>
					<option value="dkfwco_users_logged" <?php echo selected( 'logged', $filter_value, false ); ?>><?php esc_html_e( 'Logged in Users', 'filter-wc-orders' ); ?></option>
				</optgroup>
			</select>
			<?php
		}
	}

	/**
	 * Adding meta query to filter order based on selected custom filter.
	 *
	 * @param WP_Query $query WordPress query object.
	 */
	public function filter_pre_get_post_query( $query ) {
		global $pagenow;
		$filter    = filter_input( INPUT_GET, 'dkfwco_filters', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$post_type = filter_input( INPUT_GET, 'post_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$page_name = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		
		if ( $query->is_main_query() && $query->is_admin && ( 'edit.php' === $pagenow || 'wc-orders' === $page_name || 'shop_order' === $post_type ) && ! empty( $filter ) ) {
			$filter     = wc_clean( $filter );
			$filter_arr = explode( '_', $filter );

			if ( count( $filter_arr ) > 2 ) {
				$filter_type  = $filter_arr[1];
				$filter_value = $filter_arr[2];

				$meta_query = $query->get( 'meta_query' ); // Get the current "meta query".
				$meta_query = empty( $meta_query ) ? array() : $meta_query; // Declare empty array if empty.

				if ( 'payment' === $filter_type ) {
					$meta_query[] = array( // Add to "meta query".
						'meta_key' => '_payment_method',
						'value'    => $filter_value,
					);
				} elseif ( 'users' === $filter_type ) {
					$meta_query[] = array( // Add to "meta query".
						'key'     => '_customer_user',
						'value'   => 0,
						'compare' => ( 'guest' === $filter_value ) ? '=' : '>',
					);
				}
				$query->set( 'meta_query', $meta_query ); // Set the new "meta query".
			}
		}
	}

	/**
	 * Filter Order Queries.
	 *
	 * @param array $query_args Query.
	 */
	public function filter_wc_order_queries( $query_args ) {
		$filter    = filter_input( INPUT_GET, 'dkfwco_filters', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$page_name = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		if ( $this->dkfwco_is_hpos_enabled() && 'wc-orders' === $page_name && ! empty( $filter ) ) {
			$filter     = wc_clean( $filter );
			$filter_arr = explode( '_', $filter );

			if ( count( $filter_arr ) > 2 ) {
				$filter_type  = $filter_arr[1];
				$filter_value = $filter_arr[2];
			
				if ( 'payment' === $filter_type ) {
					$query_args['payment_method'] = $filter_value;
				}

				if ( 'users' === $filter_type ) {
					if ( 'guest' === $filter_value ) {
						$query_args['customer_id'] = 0;
					}
				}
			}
		}

		return $query_args;
	}

	/**
	 * Check WooCommerce HPOS is enabled.
	 *
	 * @return bool
	 */
	public function dkfwco_is_hpos_enabled() {
		return ( class_exists( '\Automattic\WooCommerce\Utilities\OrderUtil' ) && method_exists( '\Automattic\WooCommerce\Utilities\OrderUtil', 'custom_orders_table_usage_is_enabled' ) && \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled() );
	}
}
