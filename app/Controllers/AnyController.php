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

        return $this->view('albums/new');
    }

    /**
     * @return mixed
     */
    public function getNewComments()
    {
        return $this->view('comments/new');
    }
}
