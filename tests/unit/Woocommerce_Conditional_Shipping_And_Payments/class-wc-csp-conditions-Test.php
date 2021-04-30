<?php

namespace BrianHenryIE\WC_CSP_Condition_Customer\WooCommerce_Conditional_Shipping_And_Payments;

class WC_CSP_Conditions_Test extends \Codeception\Test\Unit {

	/**
	 * Verify the filter that will add the condition correctly adds the class name to the string[].
	 */
	public function test_add_condition() {

		$sut = new WC_CSP_Conditions();

		$result = $sut->add_conditions( array() );

		$this->assertIsString( $result[0] );

		$this->assertContains( 'BrianHenryIE\WC_CSP_Condition_Customer\WooCommerce_Conditional_Shipping_And_Payments\WC_CSP_Condition_Customer_Order_Count', $result );

	}

}
