<?php

namespace Swing\Controllers;

use Swing\System\Controller;

/**
 * Class ThreadController
 *
 * @package Swing\Controllers
 */
class ThreadController extends Controller
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
    public function getOne()
    {
        return view('threads/one');
    }
}
