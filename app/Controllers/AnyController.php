<?php

namespace Swing\Controllers;

use Swing\System\Controller;
use Swing\Exceptions\ForbiddenException;

/**
 * Class AnyController
 *
 * @package Swing\Controllers
 */
class AnyController extends Controller
{
    /**
     * @return mixed
     * @throws ForbiddenException
     */
    public function getNewAlbums()
    {
        if ($this->myrow->isGuest()) {
            throw new ForbiddenException('Только для зарегистрированных пользователей');
        }

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
