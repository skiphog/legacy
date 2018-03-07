<?php

namespace App\Controllers;

use System\Controller;
use App\Exceptions\ForbiddenException;

/**
 * Class PartyController
 *
 * @package App\Controllers
 */
class PartyController extends Controller
{
    /**
     * @return mixed
     */
    public function getAll()
    {
        return view('party/all');
    }

    /**
     * @return mixed
     */
    public function getOne()
    {
        return view('party/one');
    }

    /**
     * @return mixed
     * @throws ForbiddenException
     */
    public function getCreate()
    {
        $this->accessAuthUser();

        return view('party/create');
    }

    /**
     * @return mixed
     * @throws ForbiddenException
     */
    public function getEdit()
    {
        $this->accessAuthUser();

        return view('party/edit');
    }

    /**
     * @return mixed
     * @throws ForbiddenException
     */
    public function getMy()
    {
        $this->accessAuthUser();

        return view('party/my');
    }
}
