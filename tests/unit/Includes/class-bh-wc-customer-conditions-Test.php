<?php
namespace BrianHenryIE\WC_Customer_Conditions\Includes;

use BrianHenryIE\WC_Customer_Conditions\WooCommerce\Checkout;
use BrianHenryIE\WC_Customer_Conditions\WooCommerce\Coupons;
use BrianHenryIE\WC_Customer_Conditions\WooCommerce_Conditional_Shipping_And_Payments\WC_CSP_Conditions;
use Codeception\Stub\Expected;
use WP_Mock\Matcher\AnyInstance;

/**
 * Class BH_WC_Customer_Conditions_Test
 *
 * @package BrianHenryIE\WC_Customer_Conditions\Includes
 * @coversDefaultClass \BrianHenryIE\WC_Customer_Conditions\Includes\BH_WC_Customer_Conditions
 */
class BH_WC_Customer_Conditions_Test extends \Codeception\Test\Unit {

	protected function setup() : void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}


	/**
	 *
	 * @covers ::set_locale
	 *
	 * @throws \Exception
	 */
	public function test_set_locale() {

		\WP_Mock::expectActionAdded(
			'plugins_loaded',
			array( new AnyInstance( I18n::class ), 'load_plugin_textdomain' )
		);

		new BH_WC_Customer_Conditions();
	}

	/**
	 * @covers ::define_wcsp_hooks
	 */
	public function test_wcsp_hooks() {

		\WP_Mock::expectFilterAdded(
			'woocommerce_csp_conditions',
			array( new AnyInstance( WC_CSP_Conditions::class ), 'add_conditions' )
		);

		new BH_WC_Customer_Conditions();

	}

	/**
	 * @covers ::define_checkout_hooks
	 */
	public function test_checkout_hooks() {

		\WP_Mock::expectFilterAdded(
			'woocommerce_billing_fields',
			array( new AnyInstance( Checkout::class ), 'add_update_totals_class_to_billing_email' ),
			10,
			2
		);

		new BH_WC_Customer_Conditions();

	}


	/**
	 * @covers ::define_coupon_hooks
	 */
	public function test_coupon_hooks() {

		\WP_Mock::expectFilterAdded(
			'woocommerce_coupon_data_tabs',
			array( new AnyInstance( Coupons::class ), 'register_customer_tab' )
		);

		\WP_Mock::expectActionAdded(
			'woocommerce_coupon_data_panels',
			array( new AnyInstance( Coupons::class ), 'print_panel' ),
			10,
			2
		);

		\WP_Mock::expectActionAdded(
			'woocommerce_coupon_options_save',
			array( new AnyInstance( Coupons::class ), 'save' ),
			10,
			2
		);

		\WP_Mock::expectActionAdded(
			'woocommerce_coupon_is_valid',
			array( new AnyInstance( Coupons::class ), 'is_coupon_valid' ),
			10,
			3
		);

		new BH_WC_Customer_Conditions();

	}
}
