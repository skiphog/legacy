<?php

use App\Components\Auth;
use PHPUnit\Framework\TestCase;

/**
 * Class AuthTest
 */
class AuthTest extends TestCase
{

    public function testUserSingleton()
    {
        $auth = Auth::user();
        $this->assertInstanceOf(\App\Models\Myrow::class, $auth);
        $this->assertEquals($auth, Auth::user());
    }
}
