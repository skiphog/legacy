<?php

namespace Swing\Controllers;

use Swing\System\Controller;
use Swing\Exceptions\ForbiddenException;

/**
 * Class TravelController
 *
 * @package Swing\Controllers
 */
class TravelController extends Controller
{

    /**
     * @return mixed
     */
    public function getAll()
    {
        return view('travel/all');
    }

    /**
     * @return mixed
     * @throws ForbiddenException
     */
    public function getCreate()
    {
        $this->accessAuthUser();

        return view('travel/create');
    }
}
