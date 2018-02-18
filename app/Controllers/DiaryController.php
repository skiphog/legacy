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
    public function getIndex()
    {
        return $this->view('diary/index');
    }

    /**
     * @return mixed
     */
    public function getShow()
    {
        return $this->view('diary/show');
    }
}
