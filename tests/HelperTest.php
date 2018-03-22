<?php

use PHPUnit\Framework\TestCase;

/**
 * Class HelpersTest
 */
class HelperTest extends TestCase
{
    public function testApp()
    {
        $this->assertInstanceOf(StdClass::class, app(StdClass::class));
    }

    public function testConfig()
    {
        $this->assertTrue(is_bool(config('secure')));
    }

    public function testRequest()
    {
        $this->assertInstanceOf(\System\Request::class, request());
    }

    public function testDb()
    {
        $this->assertInstanceOf(\PDO::class, db());
    }

    /**
     * @runInSeparateProcess
     */
    public function testRedirect()
    {
        $this->assertInstanceOf(\System\Response::class, redirect());
    }

    /**
     * @runInSeparateProcess
     */
    public function testBack()
    {
        $this->assertInstanceOf(\System\Response::class, back());
    }

    /**
     * @runInSeparateProcess
     */
    public function testJson()
    {
        $this->assertInstanceOf(\System\Response::class, json([]));
    }

    public function testHtml()
    {
        $this->assertEquals('&lt;a&gt;test&lt;/a&gt;', html('<a>test</a>'));
    }

    public function testSubText()
    {
        $this->assertEquals('test', subText('test', 20));
        $this->assertEquals('test', subText('test', 20, '...'));

        $this->assertEquals('test', subText('test test test', 6));
        $this->assertEquals('test...', subText('test test test', 6, '...'));
    }

    public function testPlural()
    {
        $parts = 'тест|теста|тестов';
        $this->assertEquals('тест', plural(1, $parts));
        $this->assertEquals('теста', plural(2, $parts));
        $this->assertEquals('тестов', plural(0, $parts));
        $this->assertEquals('тестов', plural(30, $parts));
        $this->assertEquals('теста', plural(43, $parts));
        $this->assertEquals('тест', plural(1051, $parts));
        $this->assertEquals('', plural(1051, '1|2'));
    }

    public function testStrCompare()
    {
        $this->assertEquals(1, strCompare('test', 'test'));
        $this->assertEquals(1, strCompare('Привет', 'привет'));
        $this->assertEquals(1, strCompare('TEst', 'teST'));
        $this->assertEquals(0, strCompare('tests', 'test'));
        $this->assertEquals(0, strCompare('tesT', 'tests'));
        $this->assertEquals(0, strCompare('test`s', 'test\'s'));
    }

    public function testClearString()
    {
        $this->assertEquals('test', clearString('<a>test</a>'));
        $this->assertEquals('test', clearString('<a>test'));
        $this->assertEquals('test', clearString('test</a>'));
    }

    public function testCompress()
    {
        $this->assertEquals('<tag><tag>test<tag>', compress("\n\n<tag>\n\n <tag>test\t\n\n<tag>"));
        $this->assertEquals('<tag> <tag>test<tag>', compressOld("\n\n<tag>\n\n <tag>test\t\n\n<tag>"));
    }

    public function testRedis()
    {
        $redis = redis();
        $this->assertInstanceOf(\Predis\Client::class, $redis);
        $this->assertSame($redis, redis());
    }
}
