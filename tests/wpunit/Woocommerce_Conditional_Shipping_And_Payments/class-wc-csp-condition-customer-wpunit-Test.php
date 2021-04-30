<?php


namespace BrianHenryIE\WC_CSP_Condition_Customer\WooCommerce_Conditional_Shipping_And_Payments;



use WC_Order;

/**
 * Class WC_CSP_Condition_Customer_WPUnit_Test
 * @package BrianHenryIE\WC_CSP_Condition_Customer\WooCommerce_Conditional_Shipping_And_Payments
 * @coversDefaultClass WC_CSP_Condition_Customer
 */
class WC_CSP_Condition_Customer_WPUnit_Test extends \Codeception\TestCase\WPTestCase {


	/**
	 * When it's a regular unknown customer, we should get an empty WC_Customer object.
	 */
	public function test_unknown_customer_returns_empty_wc_customer() {

		$customer = new WC_CSP_Condition_Customer();

		$this->assertEquals( 0, $customer->get_order_count() );
		$this->assertEquals( 0, $customer->get_total_spent() );
		$this->assertEquals( false, $customer->is_paying_customer() );
	}

	/**
	 * When it's a regular logged in customer, return that user.
	 */
	public function test_registered_customer() {

		$wc_customer = new \WC_Customer();
		$wc_customer->set_email('me@example.org');
		$wc_customer->save();

		set_current_user( $wc_customer->get_id() );

		$customer = new WC_CSP_Condition_Customer();

		$this->assertEquals( $wc_customer->get_id(), $customer->get_id() );
	}

	/**
	 * When the user has entered their email address in the checkout, the user account should be found.
	 */
	public function test_find_user_from_billing_email() {

		$billing_email = __FUNCTION__ . '@example.org';

		WC()->init();
		wc_load_cart();

		WC()->customer->set_billing_email( $billing_email );

		$customer = new WC_CSP_Condition_Customer();

		$this->assertEquals( $billing_email, $customer->get_billing_email() );

	}


	/**
	 * Should:
	 *
	 * Given a user without an existing account,
	 * search past orders for any placed with the same email address,
	 * return a fake user with the appropriate values.
	 *
	 * TODO: Need to fix the plugin that associates old customer data with new user account -- in the mean time some users who sign up for an account will be shown as no past orders.
	 * @throws \WC_Data_Exception
	 */
	public function test_use_past_orders_data() {

		$email_address = __FUNCTION__ . '@example.org';

		$old_order = new WC_Order();
		$old_order->set_billing_email( $email_address );
		$old_order->set_total( '12.34' );
		$old_order->set_status( 'completed' );
		$old_order->save();


		WC()->init();
		wc_load_cart();
		WC()->customer->set_billing_email( $email_address );

		$customer_orders = wc_get_orders(
			array(
//					'status' => 'wc-completed',
				'billing_email'  => $email_address,
				'limit' => -1, // Assumes most users without accounts have somewhat small numbers of past orders.
			)
		);
		$customer_orders_completed = wc_get_orders(
			array(
                'status' => 'wc-completed',
				'billing_email'  => $email_address,
				'limit' => -1, // Assumes most users without accounts have somewhat small numbers of past orders.
			)
		);

		$customer = new WC_CSP_Condition_Customer();

		$this->assertInstanceOf( \WC_Customer::class, $customer );
		$this->assertEquals( '12.34', $customer->get_total_spent() );

	}
}
