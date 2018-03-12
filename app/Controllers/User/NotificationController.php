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

    /**
     * @return mixed
     */
    public function private()
    {
        if (!$data = auth()->getPrivateMessage()) {
            return json(['count' => 0]);
        }

        return json([
            'count' => (int)$data['count'],
            'message' => (strtotime($data['message']['pr_time']) - $_SERVER['REQUEST_TIME']) > -30
                ? render('private/message', ['message' => $data['message']])
                : ''
        ]);
    }
}
