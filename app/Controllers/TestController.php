<?php

namespace App\Controllers;

use System\Controller;
use System\Request;

/**
 * Class TestController
 *
 * @package App
 */
class TestController extends Controller
{
    public function index(Request $request)
    {
        var_dump(
            $request->getClientIp2long()
        );
    }
}
