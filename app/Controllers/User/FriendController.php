<?php

namespace App\Controllers\User;

/**
 * Class FriendController
 *
 * @package App\Controllers\User
 */
class FriendController extends UserController
{
    /**
     * @return mixed
     */
    public function index()
    {
        return view('friends/my');
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return view('friends/user');
    }

    /**
     * @return mixed
     */
    public function mutual()
    {
        return view('friends/mutual');
    }
}
