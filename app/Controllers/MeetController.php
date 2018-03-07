<?php

namespace App\Controllers;

use System\Response;
use System\Controller;
use App\Components\SwingDate;
use App\Exceptions\ForbiddenException;

/**
 * Class MeetController
 *
 * @package App\Controllers
 */
class MeetController extends Controller
{
    /**
     * Горячие знакомства
     *
     * @return mixed
     */
    public function hot()
    {
        return view('meet/hot');
    }

    /**
     * Кто меня ищет
     *
     * @return mixed
     *
     * @throws ForbiddenException
     */
    public function now()
    {
        $this->accessAuthUser();

        return view('meet/now');
    }

    /**
     * Кто онлайн
     *
     * @return mixed
     */
    public function online()
    {
        return view('meet/online');
    }

    /**
     * Новые анкеты
     *
     * @return mixed
     */
    public function new()
    {
        return view('meet/new');
    }

    /**
     * Добавить заявку в горячие знакомства
     *
     * @return Response
     */
    public function hotStore(): Response
    {
        $myrow = auth();

        if ($myrow->rate < 100) {
            abort(422, 'Недостаточно баллов');
        }

        if (!$myrow->isActive()) {
            abort(422, 'Ваша анкета не прошла модерацию. Вы не можете разместить объявление.');
        }

        $message = request()->postString('message');

        if (empty($message)) {
            return redirect('/hotmeet');
        }

        $params = [
            'message' => $message,
            'date'    => (new SwingDate())->modify('+7 day')->sqlFormat()
        ];

        $sql = 'update users 
          set hot_text = :message, hot_time = :date, rate = rate - 100 
        where id = ' . $myrow->id;

        db()->prepare($sql)->execute($params);

        return redirect('/hotmeet')->withSession('hot', 1);
    }
}
