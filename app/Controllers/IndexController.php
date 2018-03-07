<?php

namespace App\Controllers;

use System\Response;
use System\Controller;

/**
 * Class IndexController
 *
 * @package App\Controllers
 */
class IndexController extends Controller
{
    /**
     * Главная страница сайта
     *
     * @return Response
     */
    public function index(): Response
    {
        return view('index/main');
    }
}
