<?php

namespace Swing\Controllers;

use Swing\System\Controller;

/**
 * Class AlbumController
 *
 * @package Swing\Controllers
 */
class AlbumController extends Controller
{
    /**
     * Middleware
     *
     * @return bool
     */
    protected function access(): bool
    {
        return $this->myrow->isUser();
    }

    /**
     * @return mixed
     */
    public function getOne()
    {
        return view('albums/one');
    }
}
