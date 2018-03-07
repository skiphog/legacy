<?php

namespace App\Controllers;

use App\System\Controller;

/**
 * Class ThreadController
 *
 * @package App\Controllers
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
