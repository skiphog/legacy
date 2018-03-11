<?php

namespace App\Controllers\User;

use System\Controller;

/**
 * Class UserController
 *
 * @package App\Controllers\User
 */
class UserController extends Controller
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
}
