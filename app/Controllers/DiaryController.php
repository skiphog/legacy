<?php

namespace App\Controllers;

use System\Controller;

/**
 * Class DiaryController
 *
 * @package App\Controllers
 */
class DiaryController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        return view('diary/index');
    }

    /**
     * @return mixed
     */
    public function show()
    {
        return view('diary/show');
    }
}
