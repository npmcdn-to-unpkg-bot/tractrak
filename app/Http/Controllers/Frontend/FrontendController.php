<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Meet;
use URL;

/**
 * Class FrontendController
 * @package App\Http\Controllers
 */
class FrontendController extends Controller {

	/**
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
        $meets = Meet::latest()->take(5)->get();

        $return = [];
        foreach ($meets as $meet) {
            $return[] = [
                'link' => URL::route('frontend.meet.live', ['id' => $meet->id]),
                'name' => $meet->name,
                'datetime' => date('Y-m-d g:ia', strtotime($meet->meet_date)),
            ];
        }

        $data['meets'] = $return;
		return view('frontend.index', $data);
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function macros()
	{
		return view('frontend.macros');
	}
}