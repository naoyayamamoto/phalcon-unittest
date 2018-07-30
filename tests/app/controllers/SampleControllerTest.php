<?php

namespace Test;

/**
 * Class SampleControllerTest
 */
class SampleControllerTest extends \UnitTestCase
{
	public function testIndexAction() {
		$this->dispatch('/');
		$this->assertResponse(200, [1 ,2, 3]);
	}
}
