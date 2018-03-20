<?php

namespace App\Controllers\User;

/**
 * Class DiaryController
 *
 * @package App\Controllers\User
 */
class DiaryController extends UserController
{
    /**
     * @return mixed
     */
    public function index()
    {
        return view('diary/my');
    }

    /**
     * @return mixed
     */
    public function create()
    {
        return view('diary/create');
    }

    /**
     * @return mixed
     */
    public function edit()
    {
        return view('diary/edit');
    }
}
