<?php

namespace App\Controllers\User;

use System\Request;
use System\Controller;

/**
 * Class IndexController
 *
 * @package App\Controllers\User
 */
class IndexController extends Controller
{
    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function show(Request $request)
    {
        $user = auth();

        if($user->isUser() && $user->id === $request->getInteger('id')) {
            return view('user/profile');
        }

        var_dump($request);die;
    }
}
