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

    public function testRedirect()
    {
        $response =  new Response();

        $this->assertInstanceOf(Response::class, $response->redirect('/'));
    }
}
