<?php

namespace Swing\Controllers;

use Swing\System\Controller;

/**
 * Class IndexController
 *
 * @package Swing\Controllers
 */
class IndexController extends Controller
{
    /**
     * @return mixed
     */
    public function getIndex()
    {
        return view('index/main');
    }
}
