<?php

use System\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class ResponseTest
 */
class ResponseTest extends TestCase
{
    /**
     * @var Response $response
     */
    protected $response;

    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $this->response = new Response();
    }

    public function testSetData()
    {
        $this->assertInstanceOf(Response::class, $this->response->setData('test'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testRedirect()
    {
        $this->assertInstanceOf(Response::class, $this->response->redirect('/'));
        $this->assertEquals(302, http_response_code());
        $this->assertArraySubset(['Location: /'], xdebug_get_headers());
        $this->response->redirect('/test/test');
        $this->assertArraySubset(['Location: /test/test'], xdebug_get_headers());
    }

    /**
     * @runInSeparateProcess
     */
    public function testBack()
    {
        $this->assertInstanceOf(Response::class, $this->response->back());
        $this->assertEquals(302, http_response_code());
        $this->assertArraySubset(['Location: /'], xdebug_get_headers());
    }

    /**
     * @runInSeparateProcess
     */
    public function testJson()
    {
        $this->assertInstanceOf(Response::class, $this->response->json(['test']));
        $this->assertEquals(200, http_response_code());
        $this->assertArraySubset(['Content-Type: application/json;charset=utf-8'], xdebug_get_headers());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testJsonError()
    {
        $this->response->json("\xB1\x31");
    }

    /**
     * @runInSeparateProcess
     */
    public function testWithSession()
    {
        $this->response->redirect('/')
            ->withSession('foo', 'bar')
            ->withSession(['baz' => 'bla', 'b' => 'g']);

        $this->assertSame(['foo' => 'bar', 'baz' => 'bla', 'b' => 'g'],$_SESSION);
    }

    /**
     * @runInSeparateProcess
     */
    public function testWithCookie()
    {
        $this->response->redirect('/')
            ->withSession('foo', 'bar')
            ->withCookie('foo', 'bar');
        $this->assertNotFalse(strpos(xdebug_get_headers()[1],'Set-Cookie: foo=bar;'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testWithHeaders()
    {
        $this->response->back()
            ->withSession('foo', 'bar')
            ->withCookie('foo', 'bar')
            ->withHeaders(['test' => 'headers']);
        $this->assertEquals('test: headers',xdebug_get_headers()[2]);
    }

    /**
     * @runInSeparateProcess
     */
    public function testToString()
    {
        $this->response->setData('test string');
        $this->assertEquals('test string', (string)$this->response);
        $this->response->json(['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], json_decode((string)$this->response, true));
    }
}
