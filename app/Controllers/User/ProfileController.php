<?php

namespace App\Controllers\User;

/**
 * Class ProfileController
 *
 * @package App\Controllers
 */
class ProfileController extends UserController
{
    /**
     * @return mixed
     */
    public function index()
    {
        return view('profile/index');
    }
}
