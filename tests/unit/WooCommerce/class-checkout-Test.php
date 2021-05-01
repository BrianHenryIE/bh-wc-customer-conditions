<?php

namespace BrianHenryIE\WC_CSP_Condition_Customer\WooCommerce;

/**
 * Class WC_CSP_Conditions_Test
 * @package BrianHenryIE\WC_CSP_Condition_Customer\WooCommerce
 * @coversDefaultClass \BrianHenryIE\WC_CSP_Condition_Customer\WooCommerce\Checkout
 */
class WC_CSP_Conditions_Test extends \Codeception\Test\Unit
{

    /**
     * @covers ::add_update_totals_class_to_billing_email
     */
    public function test_class_is_added_to_billing_email_field() {

        $sut = new Checkout();

        $address_fields  = array(
            'billing_email' => array(
                'class' => array(
                    'before '
                )
            )
        );

        $country = '';

        $result = $sut->add_update_totals_class_to_billing_email( $address_fields, $country );

//        $css_classes = explode(' ', $result['billing_email']['class']);
        $css_classes = $result['billing_email']['class'];

        $this->assertContains( 'update_totals_on_change', $css_classes );

    }

}