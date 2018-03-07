<?php

/**
 * @var \App\System\Router $route
 */

// Главная страница
$route->get('/', 'IndexController@index');

//Поиск
$route->get('/findlist','FindController@index');
$route->get('/findresult','FindController@search');

// Вход и выход
$route->post('/authentication','LoginController@auth');
$route->get('/quit','LoginController@quit');