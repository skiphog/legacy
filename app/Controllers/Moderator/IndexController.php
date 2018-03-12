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
    public function parties()
    {
        return view('moderation/parties');
    }

    /**
     * @return mixed
     */
    public function statistics()
    {
        return view('moderation/statistics');
    }
}
