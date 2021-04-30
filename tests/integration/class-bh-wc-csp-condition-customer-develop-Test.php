<?php
/**
 * Class Plugin_Test. Tests the root plugin setup.
 *
 * @package BrianHenryIE\WC_CSP_Condition_Customer
 * @author     Brian Henry <BrianHenryIE@gmail.com>
 */

namespace BrianHenryIE\WC_CSP_Condition_Customer;

use BrianHenryIE\WC_CSP_Condition_Customer\Includes\BH_WC_CSP_Condition_Customer;

/**
 * Verifies the plugin has been instantiated and added to PHP's $GLOBALS variable.
 */
class Plugin_Develop_Test extends \Codeception\TestCase\WPTestCase {

	/**
	 * Test the main plugin object is added to PHP's GLOBALS and that it is the correct class.
	 */
	public function test_plugin_instantiated() {

		$this->assertArrayHasKey( 'bh_wc_csp_condition_customer', $GLOBALS );

		$this->assertInstanceOf( BH_WC_CSP_Condition_Customer::class, $GLOBALS['bh_wc_csp_condition_customer'] );
	}

}
