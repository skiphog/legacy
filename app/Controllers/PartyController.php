<?php

namespace Swing\Controllers;

use Swing\System\Controller;

/**
 * Class PartyController
 *
 * @package Swing\Controllers
 */
class PartyController extends Controller
{
    /**
     * @return mixed
     */
    public function getAll()
    {
        return view('party/all');
    }

    /**
     * @return mixed
     */
    public function getOne()
    {
        return view('party/one');
    }

    /**
     * @return mixed
     */
    public function getCreate()
    {
        return view('party/create');
    }

    /**
     * @return mixed
     */
    public function getEdit()
    {
        return view('party/edit');
    }

    /**
     * @return mixed
     */
    public function getMy()
    {
        return view('party/my');
    }
}
