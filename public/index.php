<?php

define('__PATH__', dirname(__DIR__));

require __PATH__ . '/vendor/autoload.php';

session_start();

(new \System\Bootstrap)->run();
