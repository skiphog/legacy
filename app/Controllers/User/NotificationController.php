<?php

namespace App\Controllers\User;

/**
 * Class NotificationController
 *
 * @package App\Controllers
 */
class NotificationController extends UserController
{
    /**
     * @return mixed
     */
    public function messages()
    {
        return view('notify/message');
    }

    /**
     * @return mixed
     */
    public function guests()
    {
        return view('notify/guests');
    }
}
