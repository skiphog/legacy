<?php

namespace Swing\Controllers;

use Swing\Components\SwingDate;
use Swing\System\Request;
use Swing\System\Controller;
use Swing\System\Response;

/**
 * Class MeetController
 *
 * @package Swing\Controllers
 */
class MeetController extends Controller
{
    /**
     * @return mixed
     */
    public function getMeet()
    {
        return $this->view('meet/index');
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function postMeet(Request $request): Response
    {
        if ($this->myrow->rate < 100) {
            abort(422, 'Недостаточно баллов');
        }

        if ($this->myrow->isInActive()) {
            abort(422, 'Ваша анкета не прошла модерацию. Вы не можете разместить объявление.');
        }

        $message = clearString($request->post('message'));

        if (empty($message)) {
            return redirect('/hotmeet');
        }

        $params = [
            'message' => $message,
            'date'    => (new SwingDate())->modify('+7 day')->sqlFormat()
        ];

        $sql = 'update users 
          set hot_text = :message, hot_time = :date, rate = rate - 100 
        where id = ' . $this->myrow->id;

        $this->dbh->prepare($sql)->execute($params);

        return redirect('/hotmeet')->with('hot', 1);
    }
}
