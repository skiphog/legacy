<?php

namespace App\Controllers\Moderator;

use System\Controller;

/**
 * Class ModerController
 *
 * @package App\Controllers\Moderator
 */
class ModerController extends Controller
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

    /**
     * @return mixed
     */
    public function index()
    {
        return view('moderation/index');
    }

    /**
     * @return mixed
     */
    public function party()
    {
        return view('moderation/party');
    }
}
