<?php
namespace BrianHenryIE\WC_CSP_Condition_Customer\Includes;

use BrianHenryIE\WC_CSP_Condition_Customer\WooCommerce\Checkout;
use BrianHenryIE\WC_CSP_Condition_Customer\WooCommerce_Conditional_Shipping_And_Payments\WC_CSP_Conditions;
use Codeception\Stub\Expected;
use WP_Mock\Matcher\AnyInstance;

/**
 * Class BH_WC_CSP_Condition_Customer_Test
 * @package BrianHenryIE\WC_CSP_Condition_Customer\Includes
 * @coversDefaultClass \BrianHenryIE\WC_CSP_Condition_Customer\Includes\BH_WC_CSP_Condition_Customer
 */
class BH_WC_CSP_Condition_Customer_Test extends \Codeception\Test\Unit {

    protected function _before() {
        \WP_Mock::setUp();
    }

    protected function _tearDown() {
        parent::_tearDown();
        \WP_Mock::tearDown();
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

		new BH_WC_CSP_Condition_Customer(  );
	}

	/**
	 * @covers ::define_wcsp_hooks
	 *
	 */
	public function test_wcsp_hooks() {

        \WP_Mock::expectFilterAdded(
            'woocommerce_csp_conditions',
            array( new AnyInstance( WC_CSP_Conditions::class ), 'add_conditions' )
        );

        new BH_WC_CSP_Condition_Customer(  );

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

        new BH_WC_CSP_Condition_Customer(  );

    }
}
