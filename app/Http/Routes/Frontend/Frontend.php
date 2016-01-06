<?php

/**
 * Frontend Controllers
 */
get('/', 'FrontendController@index')->name('home');
get('/about', 'FrontendController@about')->name('about');
get('/requirements', 'FrontendController@requirements')->name('requirements');
get('/contact', 'FrontendController@contact')->name('contact');
get('macros', 'FrontendController@macros');

get('meet/{id}', 'MeetController@live')->name('frontend.meet.live')->where(['id' => '[0-9]+']);

get('/api/meet-event/{meetId}/{eventId}/{roundId?}/{heatId?}', 'MeetController@event')->where([
        'meedId' => '[0-9]+',
        'eventId' => '[0-9]+',
        'roundId' => '[0-9]+',
        'heatId' => '[0-9]+',
    ]);
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

	get('meet/create/new', 'MeetController@create')->name('frontend.meet.create');
	post('meet/create/action', 'MeetController@actuallyCreate')->name('frontend.meet.actuallyCreate');

    get('meet/modify/{id}', 'MeetController@edit')->name('frontend.meet.modify');
	post('meet/modify/{id}/edit', 'MeetController@actuallyEdit')->name('frontend.meet.actuallyEdit');

	get('meet/run/{id}', 'MeetController@run')->name('frontend.meet.run');
	post('meet/preLoad/{id}', 'MeetController@preLoad')->name('frontend.meet.preLoad');

    get('dropbox-start/{id}', 'MeetController@dropboxStart')->name('dropbox.start');
    get('dropbox-finish', 'MeetController@dropboxFinish');
});
