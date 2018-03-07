<?php

namespace App\Controllers;

use App\System\Controller;

/**
 * Class DiaryController
 *
 * @package App\Controllers
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
