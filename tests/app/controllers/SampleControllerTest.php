<?php

namespace Test;

/**
 * Class SampleControllerTest
 */
class SampleControllerTest extends \UnitTestCase
{
	public function testIndexAction() {
		$this->dispatch('/');
		$this->assertResponseCode(200);
		$response = $this->getJsonContent();
		$this->assertEquals(['data' => [1, 2, 3]], $response);
	}
}
