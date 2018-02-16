<?php

namespace Swing\Controllers;

use Swing\System\Request;
use Swing\System\Controller;

/**
 * Class LoginController
 *
 * @package Swing\Controllers
 */
class LoginController extends Controller
{

    public function postAuth(Request $request)
    {
        require __DIR__ . '/../Legacy/testreg.php';
    }
}
