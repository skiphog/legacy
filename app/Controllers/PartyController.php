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
    public function index()
    {
        return view('parties/index');
    }

    /**
     * @return mixed
     */
    public function show()
    {
        return view('parties/show');
    }

    /**
     * @return mixed
     * @throws ForbiddenException
     */
    public function create()
    {
        $this->accessAuthUser();

        return view('parties/create');
    }

    /**
     * @return mixed
     * @throws ForbiddenException
     */
    public function edit()
    {
        $this->accessAuthUser();

        return view('parties/edit');
    }

    /**
     * @return mixed
     * @throws ForbiddenException
     */
    public function my()
    {
        $this->accessAuthUser();

        return view('parties/my');
    }
}
