<?php

namespace App\Controllers;

use System\Controller;
use App\Exceptions\ForbiddenException;

/**
 * Class TravelController
 *
 * @package App\Controllers
 */
class TravelController extends Controller
{

    /**
     * @return mixed
     */
    public function index()
    {
        return view('travel/all');
    }

    /**
     * @return mixed
     * @throws ForbiddenException
     */
    public function create()
    {
        $this->accessAuthUser();

        return view('travel/create');
    }
}
