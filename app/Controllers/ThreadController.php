<?php

namespace App\Controllers;

use App\Exceptions\NotFoundException;
use System\Controller;
use System\Request;

/**
 * Class ThreadController
 *
 * @package App\Controllers
 */
class ThreadController extends Controller
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
     * todo:: legacy
     * @param Request $request
     *
     * @return mixed
     * @throws NotFoundException
     */
    public function redirect(Request $request)
    {
        $thread_id = $request->getInteger('id');

        $sql = 'select max(ugcomments_id) c_id, count(*) cnt
          from ugcomments 
          where ugthread_id = ' . $thread_id . ' and ugc_dlt = 0 
        group by ugthread_id';

        if (!$result = db()->query($sql)->fetch()) {
            throw new NotFoundException('Такой темы не существует');
        }

        return redirect(sprintf(
            '/viewugthread_%d_%d#com%d',
            $thread_id,
            ceil((int)$result['cnt'] / 20),
            $result['c_id']
        ));
    }

    /**
     * @return mixed
     */
    public function show()
    {
        return view('threads/show');
    }
}
