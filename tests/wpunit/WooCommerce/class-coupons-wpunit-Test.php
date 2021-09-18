<?php

namespace BrianHenryIE\WC_Customer_Conditions\WooCommerce;

use WC_Coupon;

/**
 * @coversDefaultClass \BrianHenryIE\WC_Customer_Conditions\WooCommerce\Coupons
 */
class Coupons_WPUnit_Test extends \Codeception\TestCase\WPTestCase {

	/**
	 * @covers ::save
	 */
	public function test_save_min_spend() {

		$coupon    = new WC_Coupon();
		$coupon_id = $coupon->save();

		$_POST[ Coupons::MIN_TOTAL_SPEND_META_KEY ] = '12';

		$sut = new Coupons();

		$sut->save( $coupon_id, $coupon );

		/** @var WC_Coupon $updated_coupon */
		$updated_coupon = new WC_Coupon( $coupon_id );

		$min_spend = $updated_coupon->get_meta( Coupons::MIN_TOTAL_SPEND_META_KEY, true );

		$this->assertEquals( 12, $min_spend );
	}

	/**
	 * @covers ::save
	 */
	public function test_save_min_order_count() {

		$coupon    = new WC_Coupon();
		$coupon_id = $coupon->save();

		$_POST[ Coupons::MIN_ORDER_COUNT_META_KEY ] = '5';

		$sut = new Coupons();

		$sut->save( $coupon_id, $coupon );

		/** @var WC_Coupon $updated_coupon */
		$updated_coupon = new WC_Coupon( $coupon_id );

		$min_order_count = $updated_coupon->get_meta( Coupons::MIN_ORDER_COUNT_META_KEY, true );

		$this->assertEquals( 5, $min_order_count );
	}

	/**
	 * @covers ::is_coupon_valid
	 */
	public function test_is_coupon_valid_unconfigured() {

		$coupon    = new WC_Coupon();
		$coupon_id = $coupon->save();

		$discounts = new \WC_Discounts();

		$sut = new Coupons();

		$result = $sut->is_coupon_valid( true, $coupon, $discounts );

		$this->assertTrue( $result );
	}


	/**
	 * @covers ::is_coupon_valid
	 */
	public function test_is_coupon_valid_too_few_orders() {

		$coupon = new WC_Coupon();
		$coupon->add_meta_data( Coupons::MIN_ORDER_COUNT_META_KEY, 1 );
		$coupon_id = $coupon->save();

		$discounts = new \WC_Discounts();

		$sut = new Coupons();

		$result = $sut->is_coupon_valid( true, $coupon, $discounts );

		$this->assertFalse( $result );
	}


	/**
	 * @covers ::is_coupon_valid
	 */
	public function test_is_coupon_valid_enough_orders() {

		$billing_email = 'brianhenryie@gmail.com';

		$_POST['billing_email'] = $billing_email;

		$order = new \WC_Order();
		$order->set_billing_email( $billing_email );
		$order->set_status( 'completed' );
		$order->save();

		$order = new \WC_Order();
		$order->set_billing_email( $billing_email );
		$order->set_status( 'completed' );
		$order->save();

		$coupon = new WC_Coupon();
		$coupon->add_meta_data( Coupons::MIN_ORDER_COUNT_META_KEY, 2 );
		$coupon_id = $coupon->save();

		$discounts = new \WC_Discounts();

		$sut = new Coupons();

		$result = $sut->is_coupon_valid( true, $coupon, $discounts );

		$this->assertTrue( $result );
	}


	/**
	 * @covers ::is_coupon_valid
	 */
	public function test_min_spend_not_enough() {

		$billing_email = 'brianhenryie@gmail.com';

		$_POST['billing_email'] = $billing_email;

		$order = new \WC_Order();
		$order->set_billing_email( $billing_email );
		$order->set_status( 'completed' );
		$order->set_total( 99 );
		$order->save();

		$coupon = new WC_Coupon();
		$coupon->add_meta_data( Coupons::MIN_TOTAL_SPEND_META_KEY, 100 );
		$coupon_id = $coupon->save();

		$discounts = new \WC_Discounts();

		$sut = new Coupons();

		$result = $sut->is_coupon_valid( true, $coupon, $discounts );

		$this->assertFalse( $result );
	}

	/**
	 * @covers ::is_coupon_valid
	 */
	public function test_min_spend_enough() {

		$billing_email = 'brianhenryie@example.com';

		$_POST['billing_email'] = $billing_email;

		$order = new \WC_Order();
		$order->set_billing_email( $billing_email );
		$order->set_status( 'completed' );
		$order->set_total( 99 );
		$order->save();

		$order = new \WC_Order();
		$order->set_billing_email( $billing_email );
		$order->set_status( 'completed' );
		$order->set_total( 2 );
		$order->save();

		$coupon = new WC_Coupon();
		$coupon->add_meta_data( Coupons::MIN_TOTAL_SPEND_META_KEY, 100 );
		$coupon_id = $coupon->save();

		$discounts = new \WC_Discounts();

		$sut = new Coupons();

		$result = $sut->is_coupon_valid( true, $coupon, $discounts );

		$this->assertTrue( $result );
	}

}
