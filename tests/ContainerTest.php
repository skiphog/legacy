<?php

use System\Container;
use PHPUnit\Framework\TestCase;

/**
 * Class ContainerTest
 */
class ContainerTest extends TestCase
{

    public function testGetObject()
    {
        $this->assertInstanceOf(StdClass::class, Container::get(StdClass::class));
    }

    public function testGetCachedObject()
    {
        $this->assertEquals(Container::get(StdClass::class), Container::get(StdClass::class));
    }

    public function testSet()
    {
        $class = new StdClass();
        Container::set('std', $class);
        $this->assertEquals($class, Container::get('std'));
        Container::set('std', $class);
        $this->assertEquals($class, Container::get('std'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testNotExistsClassInContainer()
    {
        Container::get('NotExists');
    }

    public function testSetClosure()
    {
        Container::set('closure', function () {
            return new StdClass();
        });

        $this->assertInstanceOf(StdClass::class, Container::get('closure'));
    }
}
