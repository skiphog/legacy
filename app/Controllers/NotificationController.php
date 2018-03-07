<?php

namespace App\Controllers;

use System\Controller;

/**
 * Class NotificationController
 *
 * @package App\Controllers
 */
class NotificationController extends Controller
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
    public function getMessage()
    {
        return view('notify/message');
    }

    /**
     * @return mixed
     */
    public function getGuests()
    {
        return view('notify/guests');
    }
}
