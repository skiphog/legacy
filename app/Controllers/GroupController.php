<?php

namespace App\Controllers;

use System\Controller;

/**
 * Class GroupController
 *
 * @package App\Controllers
 */
class GroupController extends Controller
{
    /**
     * Access
     *
     * @return bool
     */
    protected function access(): bool
    {
        return auth()->isUser();
    }

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
