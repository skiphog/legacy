<?php

namespace App\Controllers;

use App\Components\Parse\All;
use System\Controller;

/**
 * Class TestController
 *
 * @package App
 */
class TestController extends Controller
{
    public function index()
    {
        return urlencode('<script>alert("hello")</script>');
    }

    public function replaceDiary()
    {
        $dbh = db();

        $sql = 'select id_di, text_di from diary';
        $diaries = $dbh->query($sql)->fetchAll();

        $sql = 'update diary set text_di = :text where id_di = :id';
        $sth = $dbh->prepare($sql);

        foreach ($diaries as $diary) {
            $text = preg_replace('~\[img\]imgart/~', '[img]/imgart/', $diary['text_di']);

            $sth->execute([
                'text' => $text,
                'id'   => $diary['id_di']
            ]);
        }

        var_dump('ok');
    }
}
