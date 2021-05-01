<?php
/**
 * order count
 */

namespace BrianHenryIE\WC_CSP_Condition_Customer\WooCommerce_Conditional_Shipping_And_Payments;

use WC_CSP_Condition;

/**
 *
 */
class WC_CSP_Condition_Customer_Order_Count extends WC_CSP_Condition {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id                             = 'customer_order_count';
		$this->title                          = __( 'Customer Order Count', 'bh-wc-csp-condition-customer' );
		$this->supported_product_restrictions = array( 'payment_gateways', 'shipping_methods', 'shipping_countries' );
		$this->supported_global_restrictions  = array( 'payment_gateways', 'shipping_methods', 'shipping_countries' );
	}

	/**
	 * Return condition field-specific resolution message which is combined along with others into a single restriction "resolution message".
	 *
	 * i.e. tells the user how to remedy the restriction. So not hugely relevent here. Left ambiguous.
	 *
	 * @param  array{ value: float, modifier: string } $_data  Condition field data.
	 * @param  array                                   $_args  Optional arguments passed by restriction.
	 * @return string|false
	 */
	public function get_condition_resolution( $_data, $_args ) {

		$message = __( 'contact support', 'bh-wc-csp-condition-customer' );

		return $message;
	}

	/**
	 * Evaluate if the condition is in effect or not.
	 *
	 * @param  array{ value: float, modifier: string } $data  Condition field data.
	 * @param  array                                   $_args  Optional arguments passed by restriction.
	 * @return boolean Return true to restrict a gateway...
	 */
	public function check_condition( $data, $_args ): bool {

		// Empty conditions always apply (not evaluated).
		if ( empty( $data['value'] ) ) {
			return true;
		}

		$wc_customer = new WC_CSP_Condition_Customer();

		$order_count = $wc_customer->get_order_count();

		if ( $this->modifier_is( $data['modifier'], array( 'min' ) ) && $data['value'] <= $order_count ) {
			return true;
		} elseif ( $this->modifier_is( $data['modifier'], array( 'max' ) ) && $data['value'] > $order_count ) {
			return true;
		}

		return false;
	}

	/**
	 * Validate, process and return condition fields.
	 *
	 * i.e. make sure it's an int & "min"|"max", then package it for saving.
	 *
	 * @param  array $posted_condition_data
	 * @return array
	 */
	public function process_admin_fields( $posted_condition_data ): ?array {

		$processed_condition_data = array();

		if ( isset( $posted_condition_data['value'] ) ) {
			$processed_condition_data['condition_id'] = $this->id;
			$processed_condition_data['value']        = intval( stripslashes( $posted_condition_data['value'] ) );
			$processed_condition_data['modifier']     = stripslashes( $posted_condition_data['modifier'] );

			if ( $processed_condition_data['value'] < 0 ) {
				return null;
			}

			if ( ! $this->modifier_is( $processed_condition_data['modifier'], array( 'max', 'min' ) ) ) {
				return null;
			}

			return $processed_condition_data;
		}

		return null;
	}

	/**
	 * Print quantity conditions content for admin product-level restriction metaboxes.
	 *
	 * @param  int                                     $index
	 * @param  int                                     $condition_index
	 * @param  array{ value: float, modifier: string } $condition_data
	 */
	public function get_admin_fields_html( $index, $condition_index, $condition_data ): void {

		$modifier = '';
		$quantity = '';

		if ( ! empty( $condition_data['modifier'] ) ) {
			$modifier = $condition_data['modifier'];
		}

		if ( ! empty( $condition_data['value'] ) ) {
			$quantity = wc_format_decimal( $condition_data['value'] );
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
				<input type="number" class="short qty" name="restriction[<?php echo $index; ?>][conditions][<?php echo $condition_index; ?>][value]" value="<?php echo $quantity; ?>" placeholder="" step="any" min="0"/>
			</div>
		</div>
		<?php
	}
}
