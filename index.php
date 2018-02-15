<?php

const __PATH__ = __DIR__;

require __PATH__ . '/vendor/autoload.php';

session_start();

\Swing\System\BootStrap::run();
