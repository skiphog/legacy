<?php

namespace App\Controllers;

use App\Components\Auth;
use System\Response;
use System\Controller;

/**
 * Class LoginController
 *
 * @package App\Controllers
 */
class AuthController extends Controller
{
    /**
     * Вход
     *
     * @return mixed
     */
    public function auth()
    {
        if (auth()->isUser()) {
            return redirect('/profile');
        }

        return require __DIR__ . '/../legacy/testreg.php';
    }

    /**
     * Выход
     *
     * @return Response
     */
    public function quit(): Response
    {
        if (auth()->isUser()) {
            Auth::quit();
        }

        return redirect('/');
    }

    /**
     * @return mixed
     */
    public function register()
    {
        if (auth()->isUser()) {
            return redirect('/profile');
        }

        return view('auth/register');
    }

    /**
     * @return mixed
     */
    public function store()
    {
        return require __DIR__ . '/../legacy/save_user.php';
    }
}
