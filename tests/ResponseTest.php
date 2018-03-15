<?php

use System\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class ResponseTest
 */
class ResponseTest extends TestCase
{
    public function testSetData()
    {
        $response =  new Response();
        $this->assertInstanceOf(Response::class, $response->setData('test'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testRedirect()
    {
        $response =  new Response();

        $this->assertInstanceOf(Response::class, $response->redirect());
        $this->assertEquals(302, http_response_code());
        $this->assertArraySubset(['Location: /'], xdebug_get_headers());
        $response->redirect('/test/test');
        $this->assertArraySubset(['Location: /test/test'], xdebug_get_headers());
    }

    /**
     * @runInSeparateProcess
     */
    public function testJson()
    {
        $response =  new Response();

        $this->assertInstanceOf(Response::class, $response->json(['test']));
        $this->assertEquals(200, http_response_code());
        $this->assertArraySubset(['Content-Type: application/json;charset=utf-8'], xdebug_get_headers());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testJsonError()
    {
        $response = new Response();

        $response->json("\xB1\x31");
    }

    /**
     * @runInSeparateProcess
     */
    public function testWithSession()
    {
        $response = new Response();

        $response->redirect()
            ->withSession('foo', 'bar')
            ->withSession(['baz' => 'bla', 'b' => 'g']);

        $this->assertSame(['foo' => 'bar', 'baz' => 'bla', 'b' => 'g'],$_SESSION);
    }

    /**
     * @runInSeparateProcess
     */
    public function testWithCookie()
    {
        $response = new Response();

        $response->redirect()
            ->withSession('foo', 'bar')
            ->withCookie('foo', 'bar');
        $this->assertNotFalse(strpos(xdebug_get_headers()[1],'Set-Cookie: foo=bar;'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testWithHeaders()
    {
        $response = new Response();

        $response->redirect()
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
        $response = new Response();

        $response->setData('test string');
        $this->assertEquals('test string', (string)$response);
        $response->json(['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], json_decode((string)$response, true));
    }
}
