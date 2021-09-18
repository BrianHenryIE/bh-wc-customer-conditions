<?php
/**
 * is_paying_customer()
 */

namespace BrianHenryIE\WC_Customer_Conditions\WooCommerce_Conditional_Shipping_And_Payments;

use WC_CSP_Condition;

/**
 *
 *
 */
class WC_CSP_Condition_Customer_Is_Paying_Customer extends WC_CSP_Condition {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id                             = 'customer_is_paying_customer';
		$this->title                          = __( 'Customer Is Paying Customer', 'bh-wc-customer-conditions' );
		$this->supported_product_restrictions = array( 'payment_gateways', 'shipping_methods', 'shipping_countries' );
		$this->supported_global_restrictions  = array( 'payment_gateways', 'shipping_methods', 'shipping_countries' );
	}

	/**
	 * Return condition field-specific resolution message which is combined along with others into a single restriction "resolution message".
	 *
	 * @param  array $data  Condition field data.
	 * @param  array $args  Optional arguments passed by restriction.
	 * @return string|false
	 */
	public function get_condition_resolution( $data, $args ) {

		$message = __( 'contact support', 'bh-wc-customer-conditions' );

		return $message;
	}

	/**
	 * Evaluate if the condition is in effect or not.
	 *
	 * @param  array $data{ modifier: string }  Condition field data.
	 * @param  array $args  Optional arguments passed by restriction.
	 * @return boolean
	 */
	public function check_condition( $data, $args ) {

		$wc_customer = new WC_CSP_Condition_Customer();

		$is_paying_customer = $wc_customer->get_is_paying_customer();

		if ( $this->modifier_is( $data['modifier'], array( 'is' ) ) ) {
			return $is_paying_customer;
		} elseif ( $this->modifier_is( $data['modifier'], array( 'is-not' ) ) ) {
			return ! $is_paying_customer;
		}

		return true;
	}

	/**
	 * Validate, process and return condition fields.
	 *
	 * @param  array $posted_condition_data
	 * @return array
	 */
	public function process_admin_fields( $posted_condition_data ) {

		$processed_condition_data                 = array();
		$processed_condition_data['condition_id'] = $this->id;
		$processed_condition_data['modifier']     = stripslashes( $posted_condition_data['modifier'] );

		return $processed_condition_data;
	}

	/**
	 * Get quantity conditions content for admin product-level restriction metaboxes.
	 *
	 * @param  int   $index
	 * @param  int   $condition_index
	 * @param  array $condition_data
	 */
	public function get_admin_fields_html( $index, $condition_index, $condition_data ): void {

		$modifier = '';

		if ( ! empty( $condition_data['modifier'] ) ) {
			$modifier = $condition_data['modifier'];
		}

		?>
		<input type="hidden" name="restriction[<?php echo $index; ?>][conditions][<?php echo $condition_index; ?>][condition_id]" value="<?php echo $this->id; ?>" />
		<div class="condition_row_inner">
			<div class="condition_modifier">
				<div class="sw-enhanced-select">
					<select name="restriction[<?php echo $index; ?>][conditions][<?php echo $condition_index; ?>][modifier]">
						<option value="is" <?php selected( $modifier, 'is', true ); ?>><?php echo __( 'Is', 'bh-wc-customer-conditions' ); ?></option>
						<option value="is-not" <?php selected( $modifier, 'is-not', true ); ?>><?php echo __( 'Is Not', 'bh-wc-customer-conditions' ); ?></option>
					</select>
				</div>
			</div>
			<div class="condition_value condition--disabled"></div>
		</div>
		<?php
	}
}
