<?php

namespace App\Controllers\Moderator;

use System\Controller;

/**
 * Class ModeratorController
 *
 * @package App\Controllers\Moderator
 */
class ModeratorController extends Controller
{
    /**
     * Access
     *
     * @return bool
     */
    protected function access(): bool
    {
        return auth()->isModerator();
    }
}
