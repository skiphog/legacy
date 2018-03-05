<?php

namespace Swing\Controllers;

use Swing\System\Controller;

/**
 * Class DiaryController
 *
 * @package Swing\Controllers
 */
class DiaryController extends Controller
{
    /**
     * @return mixed
     */
    public function getAll()
    {
        return view('diary/all');
    }

    /**
     * @return mixed
     */
    public function getOne()
    {
        return view('diary/one');
    }

    public function postOne()
    {
        
    }
}
