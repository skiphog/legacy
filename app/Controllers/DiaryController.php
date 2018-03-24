<?php

namespace App\Controllers;

use System\Controller;

/**
 * Class DiaryController
 * @todo:: добавить возможность не публиковать дневники на главной странице
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

    /**
     * @return mixed
     */
    public function user()
    {
        return view('diary/user');
    }
}
