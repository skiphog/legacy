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
    public function index()
    {
        return view('articles/index');
    }

    /**
     * @return mixed
     */
    public function show()
    {
        return view('articles/show');
    }
}
