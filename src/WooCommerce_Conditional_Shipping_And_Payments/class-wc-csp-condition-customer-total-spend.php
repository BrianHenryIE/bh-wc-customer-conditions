<?php
/**
 * total spend
 */

namespace BrianHenryIE\WC_CSP_Condition_Customer\WooCommerce_Conditional_Shipping_And_Payments;

use WC_CSP_Condition;

/**
 *
 */
class WC_CSP_Condition_Customer_Total_Spend extends WC_CSP_Condition_Customer_Abstract {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id                             = 'customer_total_spend';
		$this->title                          = __( 'Customer Total Spend', 'bh-wc-csp-condition-customer' );
		$this->supported_global_restrictions  = array( 'shipping_methods', 'shipping_countries' );
		$this->supported_product_restrictions = array( 'shipping_methods', 'shipping_countries' );
	}

	/**
	 * Return condition field-specific resolution message which is combined along with others into a single restriction "resolution message".
	 *
	 * @param  array $data  Condition field data.
	 * @param  array $args  Optional arguments passed by restriction.
	 * @return string|false
	 */
	public function get_condition_resolution( $data, $args ) {

		// Empty conditions always return false (not evaluated).
		if ( ! isset( $data['value'] ) || $data['value'] === '' ) {
			return false;
		}

		$message = false;

		if ( $this->modifier_is( $data['modifier'], array( 'min' ) ) ) {
			$message = sprintf( __( 'decrease your cart total below %s', 'bh-wc-csp-condition-customer' ), wc_price( $data['value'] ) );
		} elseif ( $this->modifier_is( $data['modifier'], array( 'max' ) ) ) {
			$message = sprintf( __( 'increase your cart total above %s', 'bh-wc-csp-condition-customer' ), wc_price( $data['value'] ) );
		}

		return $message;
	}

	/**
	 * Evaluate if the condition is in effect or not.
	 *
	 * @param  string $data  Condition field data.
	 * @param  array  $args  Optional arguments passed by restrictions.
	 * @return boolean
	 */
	public function check_condition( $data, $args ) {

		// Empty conditions always apply (not evaluated).
		if ( ! isset( $data['value'] ) || $data['value'] === '' ) {
			return true;
		}

		$cart_contents_total = WC_CSP_Core_Compatibility::is_wc_version_gte( '3.2' ) ? WC()->cart->get_cart_contents_total() : WC()->cart->cart_contents_total;
		$cart_contents_taxes = WC_CSP_Core_Compatibility::is_wc_version_gte( '3.2' ) ? WC()->cart->get_cart_contents_taxes() : WC()->cart->taxes;

		$cart_contents_tax = apply_filters( 'woocommerce_csp_cart_total_condition_incl_tax', true, $data, $args ) ? array_sum( $cart_contents_taxes ) : 0.0;
		$cart_total        = $cart_contents_total + $cart_contents_tax;

		if ( $this->modifier_is( $data['modifier'], array( 'min' ) ) && wc_format_decimal( $data['value'] ) <= $cart_total ) {
			return true;
		} elseif ( $this->modifier_is( $data['modifier'], array( 'max' ) ) && wc_format_decimal( $data['value'] ) > $cart_total ) {
			return true;
		}

		return false;
	}

	/**
	 * Validate, process and return condition fields.
	 *
	 * @param  array $posted_condition_data
	 * @return array
	 */
	public function process_admin_fields( $posted_condition_data ) {

		$processed_condition_data = array();

		if ( isset( $posted_condition_data['value'] ) ) {

			$processed_condition_data['condition_id'] = $this->id;
			$processed_condition_data['value']        = $posted_condition_data['value'] !== '0' ? wc_format_decimal( stripslashes( $posted_condition_data['value'] ), '' ) : 0;
			$processed_condition_data['modifier']     = stripslashes( $posted_condition_data['modifier'] );

			if ( $processed_condition_data['value'] > 0 || $processed_condition_data['value'] === 0 ) {
				return $processed_condition_data;
			}
		}

		return false;
	}

	/**
	 * Get cart total conditions content for admin restriction metaboxes.
	 *
	 * @param  int   $index
	 * @param  int   $condition_ndex
	 * @param  array $condition_data
	 * @return str
	 */
	public function get_admin_fields_html( $index, $condition_index, $condition_data ) {

		$modifier   = '';
		$cart_total = '';

		if ( ! empty( $condition_data['modifier'] ) ) {
			$modifier = $condition_data['modifier'];
		} else {
			$modifier = 'max';
		}

		if ( isset( $condition_data['value'] ) ) {
			$cart_total = wc_format_localized_price( $condition_data['value'] );
		}

		?>
		<input type="hidden" name="restriction[<?php echo $index; ?>][conditions][<?php echo $condition_index; ?>][condition_id]" value="<?php echo $this->id; ?>" />
		<div class="condition_row_inner">
			<div class="condition_modifier">
				<div class="sw-enhanced-select">
					<select name="restriction[<?php echo $index; ?>][conditions][<?php echo $condition_index; ?>][modifier]">
						<option value="max" <?php selected( $modifier, 'max', true ); ?>><?php echo __( '<', 'bh-wc-csp-condition-customer' ); ?></option>
						<option value="min" <?php selected( $modifier, 'min', true ); ?>><?php echo __( '>=', 'bh-wc-csp-condition-customer' ); ?></option>
					</select>
				</div>
			</div>
			<div class="condition_value">
				<input type="text" class="wc_input_price short" name="restriction[<?php echo $index; ?>][conditions][<?php echo $condition_index; ?>][value]" value="<?php echo $cart_total; ?>" placeholder="" step="any" min="0"/>
				<span class="condition_value--suffix">
					<?php echo get_woocommerce_currency_symbol(); ?>
				</span>
			</div>
		</div>
		<?php
	}
}
