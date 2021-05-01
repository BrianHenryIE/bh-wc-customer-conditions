<?php

namespace BrianHenryIE\WC_CSP_Condition_Customer\WooCommerce_Conditional_Shipping_And_Payments;

/**
 * Class WC_CSP_Conditions_Test
 * @package BrianHenryIE\WC_CSP_Condition_Customer\WooCommerce_Conditional_Shipping_And_Payments
 * @@coversDefaultClass \BrianHenryIE\WC_CSP_Condition_Customer\WooCommerce_Conditional_Shipping_And_Payments\WC_CSP_Conditions
 */
class WC_CSP_Conditions_Test extends \Codeception\Test\Unit {

	/**
	 * Verify the filter that will add the condition correctly adds the class name to the string[].
     *
     * @covers ::add_conditions
	 */
    public function test_add_is_paying_customer_condition() {

        $sut = new WC_CSP_Conditions();

        $result = $sut->add_conditions( array() );

        $this->assertIsString( $result[0] );

        $this->assertContains( 'BrianHenryIE\WC_CSP_Condition_Customer\WooCommerce_Conditional_Shipping_And_Payments\WC_CSP_Condition_Customer_Is_Paying_Customer', $result );

    }

    /**
     * @covers ::add_conditions
     */
    public function test_add_order_count_condition() {

        $sut = new WC_CSP_Conditions();

        $result = $sut->add_conditions( array() );

        $this->assertIsString( $result[0] );

        $this->assertContains( 'BrianHenryIE\WC_CSP_Condition_Customer\WooCommerce_Conditional_Shipping_And_Payments\WC_CSP_Condition_Customer_Order_Count', $result );

    }

    /**
     * @covers ::add_conditions
     */
    public function test_add_total_spend_condition() {

        $sut = new WC_CSP_Conditions();

        $result = $sut->add_conditions( array() );

        $this->assertIsString( $result[0] );

        $this->assertContains( 'BrianHenryIE\WC_CSP_Condition_Customer\WooCommerce_Conditional_Shipping_And_Payments\WC_CSP_Condition_Customer_Total_Spend', $result );

    }

}
