<?php

namespace App\Controllers;

use System\Controller;

/**
 * Class AnyController
 *
 * @package App\Controllers
 */
class AnyController extends Controller
{
    /**
     * @return mixed
     */
    public function comments()
    {
        return view('comments/index');
    }

    /**
     * @return mixed
     */
    public function birthday()
    {
        return view('birthday/index');
    }
}
