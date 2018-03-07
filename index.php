<?php

const __PATH__ = __DIR__;

require __PATH__ . '/vendor/autoload.php';

session_start();

(new \App\System\Bootstrap)->run();
