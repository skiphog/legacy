<?php

namespace App\Controllers;

use System\Controller;

/**
 * Class ArticleController
 *
 * @package App\Controllers
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
