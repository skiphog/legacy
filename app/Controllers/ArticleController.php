<?php

namespace Swing\Controllers;

use Swing\System\Controller;

/**
 * Class ArticleController
 *
 * @package Swing\Controllers
 */
class ArticleController extends Controller
{
    /**
     * @return mixed
     */
    public function getAll()
    {
        return view('articles/all');
    }

    /**
     * @return mixed
     */
    public function getOne()
    {
        return view('articles/one');
    }
}
