<?php

namespace App\Controllers;

use App\Controllers\User\UserController;

/**
 * Class GroupController
 *
 * @package App\Controllers
 */
class GroupController extends UserController
{
    /**
     * @return mixed
     */
    public function line()
    {
        return view('groups/line');
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return view('groups/index');
    }

    /**
     * @return mixed
     */
    public function new()
    {
        return view('groups/new');
    }

    /**
     * @return mixed
     */
    public function clubs()
    {
        return view('groups/clubs');
    }

    /**
     * @return mixed
     */
    public function show()
    {
        return view('groups/show');
    }
}
