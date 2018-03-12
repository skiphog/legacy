<?php

namespace App\Controllers;

use App\Controllers\User\UserController;

/**
 * Class AlbumController
 *
 * @package App\Controllers
 */
class AlbumController extends UserController
{
    /**
     * @return mixed
     */
    public function index()
    {
        return view('albums/index');
    }

    /**
     * @return mixed
     */
    public function show()
    {
        return view('albums/show');
    }
}
