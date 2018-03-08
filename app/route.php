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
$route->post('/authentication', 'LoginController@auth');
$route->get('/quit', 'LoginController@quit');

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
$route->get('/viewugthread_{id}', 'ThreadController@show', ['id' => '\d+']);

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
