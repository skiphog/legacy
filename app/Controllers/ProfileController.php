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
     * Middleware
     *
     * @return bool
     */
    protected function access(): bool
    {
        return $this->myrow->isUser();
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return view('profile/index');
    }
}
