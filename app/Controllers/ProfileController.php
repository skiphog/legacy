<?php

namespace Swing\Controllers;

use Swing\System\Controller;

/**
 * Class ProfileController
 *
 * @package Swing\Controllers
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
