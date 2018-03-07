<?php

namespace App\Controllers;

use System\Controller;

/**
 * Class ProfileController
 *
 * @package App\Controllers
 */
class ProfileController extends Controller
{
    /**
     * Access
     *
     * @return bool
     */
    protected function access(): bool
    {
        return auth()->isUser();
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return view('profile/index');
    }
}
