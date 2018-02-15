<?php

namespace Swing\Controllers;

use Swing\System\Controller;

/**
 * Class IndexController
 *
 * @package Swing\Controllers
 */
class IndexController extends Controller
{
    /**
     * @return mixed
     */
    public function actionDefault()
    {
        return $this->view('index/main');
    }
}
