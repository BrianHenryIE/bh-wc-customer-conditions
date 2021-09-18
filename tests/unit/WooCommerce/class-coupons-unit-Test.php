<?php

namespace BrianHenryIE\WC_Customer_Conditions\WooCommerce;

class Coupons_Unit_Test extends \Codeception\Test\Unit {

	public function test_register_tab() {

		\WP_Mock::userFunction(
			'__',
			array(
				'return_arg' => 0,
			)
		);

		$sut = new Coupons();

		$result = $sut->register_customer_tab( array() );

		$this->assertIsArray( $result );

		$this->assertArrayHasKey( 'customer_conditions', $result );

		$customer_conditions = $result['customer_conditions'];

		$this->assertIsArray( $customer_conditions );

		$this->assertArrayHasKey( 'label', $customer_conditions );
		$this->assertArrayHasKey( 'target', $customer_conditions );
		$this->assertArrayHasKey( 'class', $customer_conditions );

		$this->assertEquals( 'Customer Conditions', $customer_conditions['label'] );
		$this->assertEquals( 'customer_conditions_coupon_data', $customer_conditions['target'] );
		$this->assertEquals( '', $customer_conditions['class'] );

	}

}
