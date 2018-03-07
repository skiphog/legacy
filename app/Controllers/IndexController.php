<?php

namespace App\Controllers;

use App\System\Response;
use App\System\Controller;

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
