<?php
header('HTTP/1.1 404 Not Found');
header('Status: 404 Not Found');
echo '<h1>Ошибка 404 - Страницы не существует</h1>';
echo 'Попробуйте начать с <a href="/">ГЛАВНОЙ</a> страницы';
exit;