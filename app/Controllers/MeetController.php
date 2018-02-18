<?php

namespace Swing\Controllers;

use Swing\System\Response;
use Swing\System\Controller;
use Swing\Components\SwingDate;
use Swing\Exceptions\ForbiddenException;

/**
 * Class MeetController
 *
 * @package Swing\Controllers
 */
class MeetController extends Controller
{
    /**
     * Горячие знакомства
     *
     * @return mixed
     */
    public function getHotMeet()
    {
        return $this->view('meet/hot');
    }

    /**
     * Кто меня ищет
     *
     * @return mixed
     *
     * @throws ForbiddenException
     */
    public function getNowMeet()
    {
        if ($this->myrow->isGuest()) {
            throw new ForbiddenException('Только для зарегистрированных пользователей');
        }

        return $this->view('meet/now');
    }

    /**
     * Кто онлайн
     *
     * @return mixed
     */
    public function getOnlineMeet()
    {
        return $this->view('meet/online');
    }

    /**
     * Новые анкеты
     *
     * @return mixed
     */
    public function getNewMeet()
    {
        return $this->view('meet/new');
    }

    /**
     * Добавить заявку в горячие знакомства
     *
     * @return Response
     */
    public function postHotMeet(): Response
    {
        if ($this->myrow->rate < 100) {
            abort(422, 'Недостаточно баллов');
        }

        if (!$this->myrow->isActive()) {
            abort(422, 'Ваша анкета не прошла модерацию. Вы не можете разместить объявление.');
        }

        $message = clearString($this->request->post('message'));

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
