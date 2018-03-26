<?php

use System\Router;

/**
 * @var Router $route
 */

// Main page
$route->get('/', 'IndexController@index');

// Private message
$route->get('/private', 'User\NotificationController@private');

// Profile
$route->get('/profile', 'User\ProfileController@index');

// User
$route->get('/id{id:\d+}', 'User\IndexController@show');

// My
$route->group('my', function (Router $r) {
    $r->get('/dialogs', 'User\NotificationController@messages');
    $r->get('/groups/activity', 'User\GroupController@activity');
    $r->get('/guests', 'User\NotificationController@guests');
    $r->get('/groups', 'User\GroupController@index');
    $r->get('/friends', 'User\FriendController@index');
    $r->get('/diaries', 'User\DiaryController@index');
    $r->get('/parties', 'PartyController@my');
});

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
$route->group('travel', function (Router $r) {
    $r->get('/', 'TravelController@index');
    $r->get('/create', 'TravelController@create');
});

// Diary
$route->get('/viewdiary_{id:\d+}', 'DiaryController@show');

$route->group('diaries', function (Router $r) {
    $r->get('/', 'DiaryController@index');
    $r->get('/create', 'User\DiaryController@create');
    $r->get('/{id:\d+}/edit', 'User\DiaryController@edit');
    $r->get('/user/{user_id:\d+}', 'DiaryController@user');
});

// Friends
$route->group('friends', function (Router $r) {
    $r->get('/user/{user_id:\d+}', 'User\FriendController@user');
    $r->get('/user/{user_id:\d+}/mutual', 'User\FriendController@mutual');
});

// Chat
$route->get('/chat', 'ChatController@index');

// Groups
$route->group('groups', function (Router $r) {
    $r->get('/', 'GroupController@index');
    $r->get('/activity', 'GroupController@activity');
    $r->get('/new', 'GroupController@new');
    $r->get('/clubs', 'GroupController@clubs');
    $r->get('/{id:\d+}', 'GroupController@show');
});

// Threads
$route->get('/viewugthread_{id:\d+}_{page:\d+}', 'ThreadController@show');
$route->get('/viewugthread_{id:\d+}', 'ThreadController@redirect');

// Party
$route->group('parties', function (Router $r) {
    $r->get('/', 'PartyController@index');
    $r->get('/{id:\d+}', 'PartyController@show');
    $r->get('/create', 'PartyController@create');
    $r->get('/{id:\d+}/edit', 'PartyController@edit');
});

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
$route->group('moderator', function (Router $r) {
    $r->get('/list', 'Moderator\IndexController@index');
    $r->get('/parties', 'Moderator\IndexController@parties');
    $r->get('/statistics', 'Moderator\IndexController@statistics');
});

// Test
$route->get('/test', 'TestController@index');