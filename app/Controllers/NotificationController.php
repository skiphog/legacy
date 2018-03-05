<?php

namespace Swing\Controllers;

use Swing\System\Controller;

/**
 * Class NotificationController
 *
 * @package Swing\Controllers
 */
class NotificationController extends Controller
{
    /**
     * Middleware
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
