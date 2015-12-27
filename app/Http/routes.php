<?php

use App\Models\Meet;

/**
 * Switch between the included languages
 */
require(__DIR__ . "/Routes/Global/Lang.php");

/**
 * Frontend Routes
 * Namespaces indicate folder structure
 */
$router->group(['namespace' => 'Frontend'], function () use ($router)
{
	require(__DIR__ . "/Routes/Frontend/Frontend.php");
	require(__DIR__ . "/Routes/Frontend/Access.php");
});

/**
 * Backend Routes
 * Namespaces indicate folder structure
 */
$router->group(['namespace' => 'Backend'], function () use ($router)
{
	$router->group(['prefix' => 'admin', 'middleware' => 'auth'], function () use ($router)
	{
		/**
		 * These routes need view-backend permission (good if you want to allow more than one group in the backend, then limit the backend features by different roles or permissions)
		 *
		 * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
		 */
		$router->group(['middleware' => 'access.routeNeedsPermission:view-backend'], function () use ($router)
		{
			require(__DIR__ . "/Routes/Backend/Dashboard.php");
			require(__DIR__ . "/Routes/Backend/Access.php");
		});
	});
});

get('api/upcoming-meets', function() {
    $meets = Meet::latest()->take(5)->get();

    $return = [];
    foreach ($meets as $meet) {
        $return[] = [
            'link' => URL::route('frontend.meet.live', ['id' => $meet->id]),
            'name' => $meet->name,
            'datetime' => date('Y-m-d g:ia', strtotime($meet->meet_date)),
        ];
    }

    return $return;
});

get('api/current-meets', function() {

    $meets = DB::table('meets')->whereBetween('meet_date', [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:00')])->get();

    $return = [];
    foreach ($meets as $meet) {
        $return[$meet->id] = [
            'link' => URL::route('frontend.meet.live', ['id' => $meet->id]),
            'name' => $meet->name,
            'datetime' => date('Y-m-d g:ia', strtotime($meet->meet_date)),
        ];
    }

    return $return;
});
