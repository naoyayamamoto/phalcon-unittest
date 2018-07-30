<?php

namespace Test;

/**
 * Class SampleControllerTest
 */
class SampleControllerTest extends \UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testIndexAction()
    {
        $this->dispatch('/');
        $this->assertResponseCode(200);
        $response = $this->getJsonContent();
        $this->assertEquals(['data' => [1, 2, 3]], $response);
    }

    /**
     * @test
     */
    public function インデックスアクションのテスト()
    {
        $this->dispatch('/');
        $this->assertResponseCode(200);
        $response = $this->getJsonContent();
        $this->assertEquals(['data' => [1, 2, 3]], $response);
    }

    /**
     * @test
     * @dataProvider アドアクションのデータプロパイダー
     */
    public function アドアクションのテスト($a, $b, $result)
    {
        $this->dispatch("/{$a}/{$b}");
        $this->assertResponseCode(200);
        $response = $this->getJsonContent();
        $this->assertEquals(['data' => $result], $response);
    }

    public function アドアクションのデータプロパイダー()
    {
        return [
            '1 + 3 = 4' => [1, 3, 4],
            '10 + 100 = 110' => [10, 100, 110]
        ];
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function エクセプションのテスト() {
        \Exceptions::throw();
    }

    /**
     * @test
     */
    public function アサーションのテスト() {
        // trueかどうか検査
        $this->assertTrue(true);
        // 二つの引数が等しいか検査
        $a = 200;
        $this->assertEquals(200, $a);
        // 変数が空かどうか検査
        $this->assertEmpty([]);
    }
}
