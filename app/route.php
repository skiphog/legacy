<?php

/**
 * @var System\Router $route
 */

// Main page
$route->get('/', 'IndexController@index');

// Private message
$route->get('/private', 'User\NotificationController@private');

// Profile
$route->get('/profile', 'User\ProfileController@index');

// User
$route->get('/id{id:\d+}', 'User\IndexController@show');

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
$route->get('/my/diaries', 'User\DiaryController@index');
$route->get('/diaries/create', 'User\DiaryController@create');
$route->get('/diaries/{id:\d+}/edit', 'User\DiaryController@edit');

$route->get('/diary', 'DiaryController@index');
$route->get('/viewdiary_{id:\d+}', 'DiaryController@show');

// Chat
$route->get('/chat', 'ChatController@index');

// Groups
$route->get('/my/groups', 'User\GroupController@index');
$route->get('/my/groups/activity', 'User\GroupController@activity');

$route->get('/groups', 'GroupController@index');
$route->get('/groups/activity', 'GroupController@activity');
$route->get('/groups/new', 'GroupController@new');
$route->get('/groups/clubs', 'GroupController@clubs');
$route->get('/groups/{id:\d+}', 'GroupController@show');

// Threads
$route->get('/viewugthread_{id:\d+}_{page:\d+}', 'ThreadController@show');
$route->get('/viewugthread_{id:\d+}', 'ThreadController@redirect');

// Party
$route->get('/parties', 'PartyController@index');
$route->get('/parties/{id:\d+}', 'PartyController@show');
$route->get('/parties/create', 'PartyController@create');
$route->get('/parties/{id:\d+}/edit', 'PartyController@edit');
$route->get('/my/parties', 'PartyController@my');

// Albums
$route->get('/newalbums', 'AlbumController@index');
$route->get('/albums_{album_id:\d+}_{photo_id:\d+}', 'AlbumController@show');

// Story
$route->get('/story_{id:\d+}_{page:\d+}', 'ArticleController@index');
$route->get('/viewstory_{id:\d+}', 'ArticleController@show');

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

// Test
$route->get('/test', 'TestController@index');