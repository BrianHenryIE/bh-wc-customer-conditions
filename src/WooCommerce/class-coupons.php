<?php

namespace BrianHenryIE\WC_Customer_Conditions\WooCommerce;

use BrianHenryIE\WC_Customer_Conditions\WooCommerce_Conditional_Shipping_And_Payments\WC_CSP_Condition_Customer;
use WC_Coupon;
use WC_Discounts;

class Coupons {

	const MIN_ORDER_COUNT_META_KEY = 'bh_wc_customer_condition_min_order_count';
	const MIN_TOTAL_SPEND_META_KEY = 'bh_wc_customer_condition_min_total_spend';

	/**
	 * @hooked woocommerce_coupon_data_tabs
	 * @see WC_Meta_Box_Coupon_Data::output()
	 *
	 * @param array<string, array{label:string, target:string, class:string}> $tabs Indexed by the tab id.
	 * @return array<string, array{}>
	 */
	public function register_customer_tab( array $tabs ): array {

		$tabs['customer_conditions'] = array(
			'label'  => __( 'Customer Conditions', 'bh-wc-customer-conditions' ),
			'target' => 'customer_conditions_coupon_data',
			'class'  => '',
		);

		return $tabs;
	}


	/**
	 * @hooked woocommerce_coupon_data_panels
	 */
	public function print_panel( int $coupon_id, WC_Coupon $coupon ): void {

		$id     = 'customer_conditions';
		$target = 'customer_conditions_coupon_data';

		echo "<div id=\"$target\" class=\"panel woocommerce_options_panel\">";
		?>
				<div class="options_group">
					<?php

					woocommerce_wp_text_input(
						array(
							'id'                => self::MIN_ORDER_COUNT_META_KEY,
							'label'             => __( 'Minimum completed orders', 'bh-wc-customer-conditions' ),
							'default'           => 0,
							'description'       => __( 'How many completed orders the customer must have before the coupon is valid.', 'bh-wc-customer-conditions' ),
							'type'              => 'number',
							'desc_tip'          => true,
							'class'             => 'short',
							'custom_attributes' => array(
								'step' => 1,
								'min'  => 0,
							),
							'value'             => $coupon->get_meta( self::MIN_ORDER_COUNT_META_KEY, true ) ? $coupon->get_meta( self::MIN_ORDER_COUNT_META_KEY, true ) : '0',
						)
					);

					woocommerce_wp_text_input(
						array(
							'id'                => self::MIN_TOTAL_SPEND_META_KEY,
							'label'             => __( 'Minimum customer total spend', 'bh-wc-customer-conditions' ),
							'default'           => 0,
							'description'       => __( 'How much the customer must have spent in this store before the coupon is valid.', 'bh-wc-customer-conditions' ),
							'type'              => 'number',
							'desc_tip'          => true,
							'class'             => 'short',
							'custom_attributes' => array(
								'step' => 1,
								'min'  => 0,
							),
							'value'             => $coupon->get_meta( self::MIN_TOTAL_SPEND_META_KEY, true ) ? $coupon->get_meta( self::MIN_TOTAL_SPEND_META_KEY, true ) : '0',
						)
					);

					?>
				</div>
				<?php do_action( "woocommerce_coupon_options_$id", $coupon->get_id(), $coupon ); ?>
			</div>
			<?php
	}

	/**
	 * @hooked woocommerce_coupon_options_save
	 *
	 * @param int       $post_id
	 * @param WC_Coupon $coupon
	 */
	public function save( int $post_id, WC_Coupon $coupon ): void {

		$posted_data = (array) wc_clean( $_POST );

		$min_order_count = isset( $posted_data[ self::MIN_ORDER_COUNT_META_KEY ] ) ? intval( $posted_data[ self::MIN_ORDER_COUNT_META_KEY ] ) : 0;
		$min_spend       = isset( $posted_data[ self::MIN_TOTAL_SPEND_META_KEY ] ) ? intval( $posted_data[ self::MIN_TOTAL_SPEND_META_KEY ] ) : 0;

		$coupon->add_meta_data( self::MIN_ORDER_COUNT_META_KEY, "{$min_order_count}", true );
		$coupon->add_meta_data( self::MIN_TOTAL_SPEND_META_KEY, "{$min_spend}", true );
		$coupon->save();
	}

	/**
	 *
	 * @hooked woocommerce_coupon_is_valid
	 * @see WC_Discounts::is_coupon_valid()
	 *
	 * @param bool         $is_valid Or throw an exception?
	 * @param WC_Coupon    $coupon The coupon.
	 * @param WC_Discounts $discounts
	 */
	public function is_coupon_valid( bool $is_valid, WC_Coupon $coupon, WC_Discounts $discounts ): bool {

		$min_order_count = intval( $coupon->get_meta( self::MIN_ORDER_COUNT_META_KEY, true ) );
		$min_spend       = intval( $coupon->get_meta( self::MIN_TOTAL_SPEND_META_KEY, true ) );

		if ( 0 === $min_order_count && 0 === $min_spend ) {
			return $is_valid;
		}

		$wc_customer = new WC_CSP_Condition_Customer();

		if ( $min_order_count > 0 ) {

			$customer_order_count = $wc_customer->get_completed_order_count();
			if ( $customer_order_count < $min_order_count ) {
				$is_valid = false;
			}
		}

		if ( $min_spend > 0 ) {

			$customer_spend = $wc_customer->get_total_spent();
			if ( $customer_spend < $min_spend ) {
				$is_valid = false;
			}
		}

		return $is_valid;
	}
}
