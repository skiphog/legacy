<?php

namespace App\Controllers\User;

/**
 * Class GroupMasterController
 *
 * @package App\Controllers\User
 */
class GroupController extends UserController
{
    /**
     * @return Mixed
     */
    public function index()
    {
        return view('groups/my');
    }

    public function show()
    {

    }

    public function create()
    {

    }

    public function store()
    {

    }

    public function edit()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }

    /**
     * @return \System\Response
     */
    public function activity()
    {
        return view('groups/my_activity');
    }
}
