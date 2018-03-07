<?php

namespace App\Controllers;

use System\Controller;

/**
 * Class ChatController
 *
 * @package App\Controllers
 */
class ChatController extends Controller
{

    /**
     * @return \System\Response
     */
    public function index(): \System\Response
    {
        return json(['akuna' => 'matata']);
    }
}
