<?php

use System\Setting;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigTest
 */
class ConfigTest extends TestCase
{
    /**
     * @var Setting $config
     */
    protected $config;

    protected function setUp()
    {
        $this->config = new Setting();
    }

    public function testSet()
    {
        $this->assertInstanceOf(Setting::class, $this->config->set('test'));
    }

    public function testGet()
    {
        $this->config
            ->set('test', 42)
            ->set(['bar' => 'baz']);
        $this->assertEquals(42, $this->config->get('test'));
        $this->assertEquals('baz', $this->config->get('bar'));
    }

    public function testStackArray()
    {
        $this->config
            ->set('test', ['bar' => 'baz'])
            ->set(['one' => ['two' => ['three' => 42]]]);

        $this->assertsame(['bar' => 'baz'], $this->config->get('test'));
        $this->assertsame(['three' => 42], $this->config->get('one.two'));
        $this->assertEquals('baz', $this->config->get('test.bar'));
        $this->assertEquals(42, $this->config->get('one.two.three'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testEmptyParams()
    {
        $this->config->get('testfobar');
    }
}
