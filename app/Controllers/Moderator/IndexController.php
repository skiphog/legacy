<?php

namespace App\Controllers\Moderator;

/**
 * Class IndexController
 *
 * @package App\Controllers\Moderator
 */
class IndexController extends ModeratorController
{
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

    /**
     * @return mixed
     */
    public function statistic()
    {
        return view('moderation/statistics');
    }
}
