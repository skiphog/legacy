<?php

namespace Swing\Controllers;

use Swing\System\Controller;

/**
 * Class FindController
 *
 * @package Swing\Controllers
 */
class FindController extends Controller
{
    /**
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->content('find/list');
    }

    /**
     * @return mixed
     */
    public function actionSearch()
    {
        return $this->content('find/result');
    }
}
