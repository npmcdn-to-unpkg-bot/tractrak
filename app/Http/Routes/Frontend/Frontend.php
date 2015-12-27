<?php

/**
 * Frontend Controllers
 */
get('/', 'FrontendController@index')->name('home');
get('macros', 'FrontendController@macros');

get('meet/{id}', 'MeetController@live')->name('frontend.meet.live');

get('/api/meet-event/{meetId}/{eventId}', 'MeetController@event');
get('/api/dropbox', 'DropBoxController@challenge');
post('/api/dropbox', 'DropBoxController@notify');

/**
 * These frontend controllers require the user to be logged in
 */
$router->group(['middleware' => 'auth'], function ()
{
	get('dashboard', 'DashboardController@index')->name('frontend.dashboard');
	get('profile/edit', 'ProfileController@edit')->name('frontend.profile.edit');
	patch('profile/update', 'ProfileController@update')->name('frontend.profile.update');

	get('meet/create', 'MeetController@create')->name('frontend.meet.create');
    get('meet/modify', 'MeetController@edit')->name('frontend.meet.modify');
	get('meet/run/{id}', 'MeetController@run')->name('frontend.meet.run');
	post('meet/preLoad/{id}', 'MeetController@preLoad')->name('frontend.meet.preLoad');
    post('meet/create/action', 'MeetController@actuallyCreate')->name('frontend.meet.actuallyCreate');

    get('dropbox-start/{id}', 'MeetController@dropboxStart')->name('dropbox.start');
    get('dropbox-finish', 'MeetController@dropboxFinish');
});
