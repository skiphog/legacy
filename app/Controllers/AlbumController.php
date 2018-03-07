<?php

namespace App\Controllers;

use System\Controller;

/**
 * Class AlbumController
 *
 * @package App\Controllers
 */
class AlbumController extends Controller
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
