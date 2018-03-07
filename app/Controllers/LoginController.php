<?php

namespace App\Controllers;

use App\Components\Auth;
use App\System\Response;
use App\System\Controller;

/**
 * Class LoginController
 *
 * @package App\Controllers
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
