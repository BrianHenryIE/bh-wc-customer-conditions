<?php
namespace BH_WC_CSP_Condition_Customer\includes;

use BH_WC_CSP_Condition_Customer\BrianHenryIE\WPPB\WPPB_Loader_Interface;
use Codeception\Stub\Expected;

class BH_WC_CSP_Condition_Customer_Test extends \Codeception\Test\Unit {

	/**
	 *
	 * @covers BH_WC_CSP_Condition_Customer\includes\BH_WC_CSP_Condition_Customer::set_locale
	 *
	 * @throws \Exception
	 */
	public function test_set_locale() {

		$mock_loader = $this->makeEmpty(
			WPPB_Loader_Interface::class,
			array(
				'add_action' => Expected::once(),
			)
		);

		new BH_WC_CSP_Condition_Customer( $mock_loader );
	}

	/**
	 * @covers BH_WC_CSP_Condition_Customer\includes\BH_WC_CSP_Condition_Customer::define_wcsp_hooks
	 *
	 */
	public function test_construct() {

		$mock_loader = $this->makeEmpty(
			WPPB_Loader_Interface::class,
			array(
				'add_filter' => Expected::once(),
			)
		);

		new BH_WC_CSP_Condition_Customer( $mock_loader );

	}

	public function test_run() {

		$mock_loader = $this->makeEmpty(
			WPPB_Loader_Interface::class,
			array(
				'run' => Expected::once(),
			)
		);

		$sut = new BH_WC_CSP_Condition_Customer( $mock_loader );

		$sut->run();

	}

	/**
	 * Test the loader instance variable is correctly set by the constructor.
	 *
	 * @covers BH_WC_CSP_Condition_Customer\includes\BH_WC_CSP_Condition_Customer::__construct
	 *
	 * @throws \Exception
	 */
	public function test_loader() {

		$mock_loader = $this->makeEmpty( WPPB_Loader_Interface::class );

		$sut = new BH_WC_CSP_Condition_Customer( $mock_loader );

		$loader = $sut->get_loader();

		$this->assertSame( $mock_loader, $loader );

	}

}
