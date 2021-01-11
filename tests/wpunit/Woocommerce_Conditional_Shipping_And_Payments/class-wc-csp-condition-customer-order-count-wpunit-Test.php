<?php


namespace BH_WC_CSP_Condition_Customer\woocommerce_conditional_shipping_and_payments;

/**

 *
 * Class WC_CSP_Condition_Customer_Order_Count_WPUnit_Test
 * @package BH_WC_CSP_Condition_Customer\woocommerce_conditional_shipping_and_payments
 */
class WC_CSP_Condition_Customer_Order_Count_WPUnit_Test extends \Codeception\TestCase\WPTestCase {

	/**
	 * @covers BH_WC_CSP_Condition_Customer\woocommerce_conditional_shipping_and_payments\WC_CSP_Condition_Customer_Order_Count::get_condition_resolution
	 */


	/**
	 * @covers BH_WC_CSP_Condition_Customer\woocommerce_conditional_shipping_and_payments\WC_CSP_Condition_Customer_Order_Count::check_condition
	 */
	public function test_condition_zero_orders_minimum_one() {

		$customer = new \WC_Customer();
		$customer->set_email('you@exmaple.org');
		$customer->save();

		/**
		* @see get_metadata_raw()
		*
		* @param mixed  $value     The value to return, either a single metadata value or an array
		*                          of values depending on the value of `$single`. Default null.
		* @param int    $object_id ID of the object metadata is for.
		* @param string $meta_key  Metadata key.
		* @param bool   $single    Whether to return only the first value of the specified `$meta_key`.
		* @param string $meta_type Type of object metadata is for. Accepts 'post', 'comment', 'term', 'user',
		*                          or any other object type with an associated meta table.
		*/
		add_filter( "get_user_metadata", function( $result, $object_id, $meta_key, $single, $meta_type ) use ($customer) {

			if ( $customer->get_id() === $object_id && '_order_count' === $meta_key ) {
				return 0;
			}

			return $result;

		}, 10, 5);


		$sut = new WC_CSP_Condition_Customer_Order_Count();

		// Disable when the order count is max 1 (or under).
		$data = array(
			'modifier' => 'max',
			'value' => '1'
		);

		$result = $sut->check_condition( $data, null );

		// true means should restrict.
		$this->assertTrue( $result );

	}


	/**
	 * @covers BH_WC_CSP_Condition_Customer\woocommerce_conditional_shipping_and_payments\WC_CSP_Condition_Customer_Order_Count::process_admin_fields
	 */


	/**
	 * @covers BH_WC_CSP_Condition_Customer\woocommerce_conditional_shipping_and_payments\WC_CSP_Condition_Customer_Order_Count::get_admin_fields_html
	 */

}
