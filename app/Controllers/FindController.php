<?php

namespace App\Controllers;

use System\Controller;

/**
 * Class FindController
 *
 * @package App\Controllers
 */
class FindController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        return view('find/list');
    }

    /**
     * @return mixed
     */
    public function search()
    {
        return view('find/result');
    }
}
