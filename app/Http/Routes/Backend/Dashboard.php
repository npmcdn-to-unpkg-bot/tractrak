<?php

get('dashboard', 'DashboardController@index')->name('backend.dashboard');

get('edit/team', 'AdminController@selectTeamToEdit')->name('admin.edit.team.select');
get('edit/team/{id}', 'AdminController@editTeam')->name('admin.edit.team');
post('edit/team/{id}', 'AdminController@saveEditTeam')->name('admin.edit.saveTeam');

get('edit/athlete', 'AdminController@selectAthleteToEdit')->name('admin.edit.athlete.select');
get('edit/athlete/{id}', 'AdminController@editAthlete')->name('admin.edit.athlete');
post('edit/athlete/{id}', 'AdminController@editAthlete')->name('admin.edit.athlete');