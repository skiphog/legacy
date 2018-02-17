<?php

namespace Swing\Controllers;

use Swing\System\Request;
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
     * @param Request $request
     *
     * @return mixed
     */
    public function postAuth(Request $request)
    {
        if ($this->myrow->isUser()) {
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
        if ($this->myrow->isGuest()) {
            return redirect('/');
        }

        unset($_SESSION['id'], $_SESSION['login'], $_SESSION['password']);

        $time = time() - 3600;
        $domain = config('domain');

        foreach (['id', 'login', 'password'] as $value) {
            setcookie($value, '', $time, '/', $domain);
        }

        return redirect('/');
    }
}
