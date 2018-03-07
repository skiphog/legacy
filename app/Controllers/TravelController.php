<?php

namespace App\Controllers;

use App\System\Controller;
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
