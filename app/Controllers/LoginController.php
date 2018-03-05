<?php

namespace Swing\Controllers;

use Swing\Components\Auth;
use Swing\System\Response;
use Swing\System\Controller;

/**
 * Class LoginController
 *
 * @package Swing\Controllers
 */
class LoginController extends Controller
{

    /**
     * Вход
     *
     * @return mixed
     */
    public function postAuth()
    {
        if (auth()->isUser()) {
            return redirect('/profile');
        }

        return require __DIR__ . '/../Legacy/testreg.php';
    }

    /**
     * Выход
     *
     * @return Response
     */
    public function getQuit(): Response
    {
        if (auth()->isUser()) {
            Auth::quit();
        }

        return redirect('/');
    }
}
