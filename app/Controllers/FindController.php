<?php

namespace Swing\Controllers;

use Swing\System\Controller;

/**
 * Class FindController
 *
 * @package Swing\Controllers
 */
class FindController extends Controller
{
    /**
     * @return mixed
     */
    public function getIndex()
    {
        return view('find/list');
    }

    /**
     * @return mixed
     */
    public function getSearch()
    {
        return view('find/result');
    }
}
