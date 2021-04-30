<?php
/**
 * is_paying_customer()
 */

namespace BH_WC_CSP_Condition_Customer\woocommerce_conditional_shipping_and_payments;

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
		$this->title                          = __( 'Customer Is Paying Customer', 'bh-wc-csp-condition-customer' );
		$this->supported_product_restrictions = array( 'payment_gateways' );
		$this->supported_global_restrictions  = array( 'payment_gateways' );
	}

	/**
	 * Return condition field-specific resolution message which is combined along with others into a single restriction "resolution message".
	 *
	 * @param  array $data  Condition field data.
	 * @param  array $args  Optional arguments passed by restriction.
	 * @return string|false
	 */
	public function get_condition_resolution( $data, $args ) {

		$cart_contents = WC()->cart->get_cart();

		if ( empty( $cart_contents ) ) {
			return false;
		}

		$message = false;

		if ( $this->modifier_is( $data['modifier'], array( 'in' ) ) ) {
			$message = __( 'remove all on sale products from your cart', 'bh-wc-csp-condition-customer' );
		} elseif ( $this->modifier_is( $data['modifier'], array( 'not-in' ) ) ) {
			$message = __( 'add some on sale products to your cart', 'bh-wc-csp-condition-customer' );
		} elseif ( $this->modifier_is( $data['modifier'], array( 'all-in' ) ) ) {
			$message = __( 'make sure that your cart doesn\'t contain only products on sale', 'bh-wc-csp-condition-customer' );
		} elseif ( $this->modifier_is( $data['modifier'], array( 'not-all-in' ) ) ) {
			$message = __( 'make sure that your cart contains only products on sale', 'bh-wc-csp-condition-customer' );
		}

		return $message;
	}

	/**
	 * Evaluate if the condition is in effect or not.
	 *
	 * @param  array $data  Condition field data.
	 * @param  array $args  Optional arguments passed by restriction.
	 * @return boolean
	 */
	public function check_condition( $data, $args ) {

		$contains_items_on_sale = false;
		$all_items_on_sale      = true;

		if ( ! empty( $args['order'] ) ) {

			$order       = $args['order'];
			$order_items = $order->get_items( 'line_item' );

			if ( ! empty( $order_items ) ) {

				foreach ( $order_items as $order_item ) {

					$product = $order->get_product_from_item( $order_item );

					if ( $product ) {

						if ( $product->is_on_sale() ) {

							$contains_items_on_sale = true;

							if ( $this->modifier_is( $data['modifier'], array( 'in', 'not-in' ) ) ) {
								break;
							}
						} else {

							$all_items_on_sale = false;

							if ( $this->modifier_is( $data['modifier'], array( 'all-in', 'not-all-in' ) ) ) {
								break;
							}
						}
					}
				}
			}
		} else {

			$cart_contents = WC()->cart->get_cart();

			if ( ! empty( $cart_contents ) ) {

				foreach ( $cart_contents as $cart_item_key => $cart_item ) {

					$product = $cart_item['data'];

					if ( $product->is_on_sale() ) {

						$contains_items_on_sale = true;

						if ( $this->modifier_is( $data['modifier'], array( 'in', 'not-in' ) ) ) {
							break;
						}
					} else {

						$all_items_on_sale = false;

						if ( $this->modifier_is( $data['modifier'], array( 'all-in', 'not-all-in' ) ) ) {
							break;
						}
					}
				}
			}
		}

		if ( $this->modifier_is( $data['modifier'], array( 'in' ) ) && $contains_items_on_sale ) {
			return true;
		} elseif ( $this->modifier_is( $data['modifier'], array( 'not-in' ) ) && ! $contains_items_on_sale ) {
			return true;
		} elseif ( $this->modifier_is( $data['modifier'], array( 'all-in' ) ) && $all_items_on_sale ) {
			return true;
		} elseif ( $this->modifier_is( $data['modifier'], array( 'not-all-in' ) ) && ! $all_items_on_sale ) {
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
	 * @return string
	 */
	public function get_admin_fields_html( $index, $condition_index, $condition_data ) {

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
						<option value="in" <?php selected( $modifier, 'in', true ); ?>><?php echo __( 'in cart', 'bh-wc-csp-condition-customer' ); ?></option>
						<option value="not-in" <?php selected( $modifier, 'not-in', true ); ?>><?php echo __( 'not in cart', 'bh-wc-csp-condition-customer' ); ?></option>
						<option value="all-in" <?php selected( $modifier, 'all-in', true ); ?>><?php echo __( 'all cart items', 'bh-wc-csp-condition-customer' ); ?></option>
						<option value="not-all-in" <?php selected( $modifier, 'not-all-in', true ); ?>><?php echo __( 'not all cart items', 'bh-wc-csp-condition-customer' ); ?></option>
					</select>
				</div>
			</div>
			<div class="condition_value condition--disabled"></div>
		</div>
		<?php
	}
}
