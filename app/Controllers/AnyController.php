<?php

namespace App\Controllers;

use System\Controller;
use App\Exceptions\ForbiddenException;

/**
 * Class AnyController
 *
 * @package App\Controllers
 */
class AnyController extends Controller
{
    /**
     * @return mixed
     * @throws ForbiddenException
     */
    public function getNewAlbums()
    {
        $this->accessAuthUser();

        return view('albums/new');
    }

    /**
     * @return mixed
     */
    public function getNewComments()
    {
        return view('comments/new');
    }

    /**
     * @return mixed
     */
    public function getAllBirthday()
    {
        return view('birthday/all');
    }
}
