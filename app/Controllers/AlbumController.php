<?php

namespace App\Controllers;

use App\System\Controller;

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
    public function getOne()
    {
        return view('albums/one');
    }
}
