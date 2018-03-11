<?php

namespace App\Controllers\Admin;

use System\Controller;

/**
 * Class AdminController
 *
 * @package App\Controllers\Admin
 */
class AdminController extends Controller
{
    /**
     * Access
     *
     * @return bool
     */
    protected function access(): bool
    {
        return auth()->isAdmin();
    }
}
