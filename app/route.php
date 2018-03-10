<?php

/**
 * @var System\Router $route
 */

// Main page
$route->get('/', 'IndexController@index');

// Profile
$route->get('/profile', 'ProfileController@index');

// Dialogs
$route->get('/newmydialog', 'NotificationController@messages');
$route->get('/whoisloock', 'NotificationController@guests');

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
$route->get('/newugthreads', 'GroupController@line');
$route->get('/ugrouplist', 'GroupController@index');
$route->get('/ugrouplist_new', 'GroupController@new');
$route->get('/ugrouplist_clubs', 'GroupController@clubs');
$route->get('/viewugroup_{id}', 'GroupController@show', ['id' => '\d+']);

// Threads
$route->get('/viewugthread_{id}_{page}', 'ThreadController@show', ['id' => '\d+', 'page' => '\d+']);
$route->get('/viewugthread_{id}', 'ThreadController@redirect', ['id' => '\d+']);

// Party
$route->get('/all_events', 'PartyController@index');
$route->get('/event_{id}', 'PartyController@show', ['id' => '\d+']);
$route->get('/event/create', 'PartyController@create');
$route->get('/event/{id}/edit', 'PartyController@edit', ['id' => '\d+']);
$route->get('/my_events', 'PartyController@my');

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
$route->get('/moderator/list', 'Moderator\ModerController@index');
$route->get('/moderator/party', 'Moderator\ModerController@party');
$route->get('/moderator/statistic', 'Moderator\ModerController@statistic');
