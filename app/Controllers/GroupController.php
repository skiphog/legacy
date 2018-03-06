<?php

namespace Swing\Controllers;

use Swing\System\Controller;

/**
 * Class GroupController
 *
 * @package Swing\Controllers
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
    public function getEventLine()
    {
        return view('groups/line');
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return view('groups/all');
    }

    /**
     * @return mixed
     */
    public function getNew()
    {
        return view('groups/new');
    }

    /**
     * @return mixed
     */
    public function getClubs()
    {
        return view('groups/clubs');
    }

    /**
     * @return mixed
     */
    public function getOne()
    {
        return view('groups/one');
    }
}
