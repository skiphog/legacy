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
}
