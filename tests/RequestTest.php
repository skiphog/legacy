<?php

use System\Request;
use PHPUnit\Framework\TestCase;

/**
 * Class RequestTest
 */
class RequestTest extends TestCase
{
    /**
     * @var Request $request
     */
    protected $request;

    protected function setUp()
    {
        $_GET = ['test' => 'get', 'int' => '1'];
        $_POST = ['test' => 'post', 'int' => '1'];
        $_SERVER['REQUEST_URI'] = '/index/page?g=1&test2';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $this->request = new Request();
    }

    protected function tearDown()
    {
        $_GET = $_POST = [];
    }

    public function testGet()
    {
        $this->assertSame($_GET, $this->request->get());
        $this->assertNull($this->request->get('any'));
    }

    public function testPost()
    {
        $this->assertSame($_POST, $this->request->post());
        $this->assertNull($this->request->post('any'));
    }

    public function testGetInager()
    {
        $this->assertSame(0, $this->request->getInteger('any'));
        $this->assertSame(0, $this->request->getInteger('test'));
        $this->assertSame(0, $this->request->getInteger(''));
        $this->assertSame(['any' => 0, 'test' => 0], $this->request->getInteger(['any', 'test']));
        $this->assertSame(['any' => 0, 'test' => 0, 'int' => 1], $this->request->getInteger(['any', 'test', 'int']));
    }

    public function testPostInager()
    {
        $this->assertSame(0, $this->request->postInteger('any'));
        $this->assertSame(0, $this->request->postInteger('test'));
        $this->assertSame(0, $this->request->postInteger(''));
        $this->assertSame(['any' => 0, 'test' => 0], $this->request->postInteger(['any', 'test']));
        $this->assertSame(['any' => 0, 'test' => 0, 'int' => 1], $this->request->postInteger(['any', 'test', 'int']));
    }

    public function testGetString()
    {
        $this->assertSame('', $this->request->getString('any'));
        $this->assertSame('get', $this->request->getString('test'));
        $this->assertSame('1', $this->request->getString('int'));
        $this->assertSame(['any' => '', 'test' => 'get'], $this->request->getString(['any', 'test']));
        $this->assertSame(['any' => '', 'test' => 'get', 'int' => '1'],
            $this->request->getString(['any', 'test', 'int']));
    }

    public function testPostString()
    {
        $this->assertSame('', $this->request->postString('any'));
        $this->assertSame('post', $this->request->postString('test'));
        $this->assertSame('1', $this->request->postString('int'));
        $this->assertSame(['any' => '', 'test' => 'post'], $this->request->postString(['any', 'test']));
        $this->assertSame(['any' => '', 'test' => 'post', 'int' => '1'],
            $this->request->postString(['any', 'test', 'int']));
    }

    public function testGetValues()
    {
        $this->assertTrue(is_array($this->request->getValues()));
        $this->assertSame(['get', '1'], $this->request->getValues());
        $this->assertSame(['get'], $this->request->getValues('test'));
        $this->assertSame([null], $this->request->getValues('any'));
        $this->assertSame([null, null], $this->request->getValues(['any', 'any2']));
    }

    public function testPostValues()
    {
        $this->assertTrue(is_array($this->request->postValues()));
        $this->assertSame(['post', '1'], $this->request->postValues());
        $this->assertSame(['post'], $this->request->postValues('test'));
        $this->assertSame([null], $this->request->postValues('any'));
        $this->assertSame([null, null], $this->request->postValues(['any', 'any2']));
    }

    public function testGetValuesInteger()
    {
        $this->assertTrue(is_array($this->request->getValuesInteger()));
        $this->assertSame([0, 1], $this->request->getValuesInteger());
        $this->assertSame([1], $this->request->getValuesInteger('int'));
        $this->assertSame([0], $this->request->getValuesInteger('any'));
        $this->assertSame([0, 0], $this->request->getValuesInteger(['any', 'any2']));
    }

    public function testPostValuesInteger()
    {
        $this->assertTrue(is_array($this->request->postValuesInteger()));
        $this->assertSame([0, 1], $this->request->postValuesInteger());
        $this->assertSame([1], $this->request->postValuesInteger('int'));
        $this->assertSame([0], $this->request->postValuesInteger('any'));
        $this->assertSame([0, 0], $this->request->postValuesInteger(['any', 'any2']));
    }

    public function testGetValuesString()
    {
        $this->assertTrue(is_array($this->request->getValuesString()));
        $this->assertSame(['get', '1'], $this->request->getValuesString());
        $this->assertSame(['1'], $this->request->getValuesString('int'));
        $this->assertSame([''], $this->request->getValuesString('any'));
        $this->assertSame(['', ''], $this->request->getValuesString(['any', 'any2']));
    }

    public function testPostValuesString()
    {
        $this->assertTrue(is_array($this->request->postValuesString()));
        $this->assertSame(['post', '1'], $this->request->postValuesString());
        $this->assertSame(['1'], $this->request->postValuesString('int'));
        $this->assertSame([''], $this->request->postValuesString('any'));
        $this->assertSame(['', ''], $this->request->postValuesString(['any', 'any2']));
    }

    public function testSetAttributes()
    {
        $this->request->setAttributes([
            1 => 'test',
            'id' => 2,
            'name' => 'Vasya',
            '5' => 'string 5'
        ]);

        $this->assertSame(['test' => 'get', 'int' => '1', 'id' => 2, 'name' => 'Vasya'], $this->request->get());
    }

    public function testUri()
    {
        $this->assertEquals('index/page', $this->request->uri());
    }

    public function testType()
    {
        $this->assertEquals('POST',$this->request->type());
    }

    public function testIsAjax()
    {
        $this->assertTrue($this->request->isAjax());
    }
}
