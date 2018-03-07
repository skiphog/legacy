<?php

namespace App\Controllers;

use System\Controller;

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

    protected function before(): void
    {

    }

    /**
     * @return mixed
     */
    public function show()
    {
        return view('threads/show');
    }
}
