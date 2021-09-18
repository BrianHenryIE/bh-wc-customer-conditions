<?php
/**
 * total spend
 */

namespace BrianHenryIE\WC_Customer_Conditions\WooCommerce_Conditional_Shipping_And_Payments;

use WC_CSP_Condition;

/**
 *
 */
class WC_CSP_Condition_Customer_Total_Spend extends WC_CSP_Condition {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id                             = 'customer_total_spend';
		$this->title                          = __( 'Customer Total Spend', 'bh-wc-customer-conditions' );
		$this->supported_product_restrictions = array( 'payment_gateways', 'shipping_methods', 'shipping_countries' );
		$this->supported_global_restrictions  = array( 'payment_gateways', 'shipping_methods', 'shipping_countries' );
	}

	/**
	 * Return condition field-specific resolution message which is combined along with others into a single restriction "resolution message".
	 *
	 * @param  array{ value: float, modifier: string } $data  Condition field data.
	 * @param  array                                   $args  Optional arguments passed by restriction.
	 *
	 * @return string
	 */
	public function get_condition_resolution( $data, $args ): string {

		$message = __( 'contact support', 'bh-wc-customer-conditions' );

		return $message;
	}

	/**
	 * Evaluate if the condition is in effect or not.
	 *
	 * @param  array $data{ value: float, modifier: string }  Condition field data.
	 * @param  array $args  Optional arguments passed by restrictions.
	 * @return boolean
	 */
	public function check_condition( $data, $_args ): bool {

		// Empty conditions always apply (not evaluated).
		if ( empty( $data['value'] ) ) {
			return true;
		}

		$wc_customer = new WC_CSP_Condition_Customer();

		$customer_lifetime_total_spend = $wc_customer->get_total_spent();

		if ( $this->modifier_is( $data['modifier'], array( 'min' ) ) && $data['value'] <= $customer_lifetime_total_spend ) {
			return true;
		} elseif ( $this->modifier_is( $data['modifier'], array( 'max' ) ) && $data['value'] > $customer_lifetime_total_spend ) {
			return true;
		}

		return false;
	}

	/**
	 * Validate, process and return condition fields.
	 *
	 * @param  array{ value: string, modifier: string } $posted_condition_data
	 * @return array{ value: float, modifier: string }
	 */
	public function process_admin_fields( $posted_condition_data ): ?array {

		$processed_condition_data = array();

		if ( isset( $posted_condition_data['value'] ) ) {

			$processed_condition_data['value']    = '0' !== $posted_condition_data['value'] ? wc_format_decimal( stripslashes( $posted_condition_data['value'] ), '' ) : 0;
			$processed_condition_data['modifier'] = stripslashes( $posted_condition_data['modifier'] );

			if ( $processed_condition_data['value'] >= 0 ) {
				return $processed_condition_data;
			}
		}

		return null;
	}

	/**
	 * Print conditions config content for admin restriction metaboxes.
	 *
	 * @param  int                                     $index
	 * @param  int                                     $condition_index
	 * @param  array{ value: float, modifier: string } $condition_data
	 */
	public function get_admin_fields_html( $index, $condition_index, $condition_data ): void {

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
						<option value="max" <?php selected( $modifier, 'max', true ); ?>><?php echo __( '<', 'bh-wc-customer-conditions' ); ?></option>
						<option value="min" <?php selected( $modifier, 'min', true ); ?>><?php echo __( '>=', 'bh-wc-customer-conditions' ); ?></option>
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
