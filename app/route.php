<?php

/**
 * @var System\Router $route
 */

// Main page
$route->get('/', 'IndexController@index');

// Profile
$route->get('/profile', 'User\ProfileController@index');

// User
$route->get('/id{id}', 'User\IndexController@show', ['id' => '\d+']);

// Dialogs
$route->get('/my/dialogs', 'User\NotificationController@messages');
$route->get('/my/guests', 'User\NotificationController@guests');

//Search
$route->get('/findlist', 'FindController@index');
$route->get('/findresult', 'FindController@search');

// Login and exit
$route->get('/registration', 'AuthController@register');
$route->get('/quit', 'AuthController@quit');
$route->post('/authentication', 'AuthController@auth');
$route->post('/regsave', 'AuthController@store');

// Meeting
$route->get('/hotmeet', 'MeetController@hot');
$route->post('/hotmeet', 'MeetController@hotStore');
$route->get('/nowmeet', 'MeetController@now');
$route->get('/onlinemeet', 'MeetController@online');
$route->get('/newmeet', 'MeetController@new');

// Travel
$route->get('/travel', 'TravelController@index');
$route->get('/travel/create', 'TravelController@create');

// Diary
$route->get('/diary', 'DiaryController@index');
$route->get('/viewdiary_{id}', 'DiaryController@show', ['id' => '\d+']);

// Chat
$route->get('/chat', 'ChatController@index');

// Groups
$route->get('/my/groups', 'User\GroupController@index');

$route->get('/groups', 'GroupController@index');
$route->get('/groups/activity', 'GroupController@activity');
$route->get('/groups/new', 'GroupController@new');
$route->get('/groups/clubs', 'GroupController@clubs');
$route->get('/groups/{id}', 'GroupController@show', ['id' => '\d+']);

// Threads
$route->get('/viewugthread_{id}_{page}', 'ThreadController@show', ['id' => '\d+', 'page' => '\d+']);
$route->get('/viewugthread_{id}', 'ThreadController@redirect', ['id' => '\d+']);

// Party
$route->get('/parties', 'PartyController@index');
$route->get('/parties/{id}', 'PartyController@show', ['id' => '\d+']);
$route->get('/parties/create', 'PartyController@create');
$route->get('/parties/{id}/edit', 'PartyController@edit', ['id' => '\d+']);
$route->get('/my/parties', 'PartyController@my');

// Albums
$route->get('/newalbums', 'AlbumController@index');
$route->get('/albums_{album_id}_{photo_id}', 'AlbumController@show', ['album_id' => '\d+', 'photo_id' => '\d+']);

// Story
$route->get('/story_{id}_{page}', 'ArticleController@index', ['id' => '\d+', 'page' => '\d+']);
$route->get('/viewstory_{id}', 'ArticleController@show', ['id' => '\d+']);

// Any
$route->get('/last_comments', 'AnyController@comments');
$route->get('/birthday', 'AnyController@birthday');

// Donate
$route->get('/donate', 'AnyController@donate');

// Personal data
$route->get('/personal', 'AnyController@personal');

// Moderation
$route->get('/moderator/list', 'Moderator\IndexController@index');
$route->get('/moderator/parties', 'Moderator\IndexController@parties');
$route->get('/moderator/statistics', 'Moderator\IndexController@statistics');
