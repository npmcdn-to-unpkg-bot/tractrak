<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Meet;
use Illuminate\View\View;
use URL;

/**
 * Class FrontendController
 * @package App\Http\Controllers
 */
class FrontendController extends Controller
{
    /**
     * @return View
     */
    public function index()
    {
        $recentMeets = Meet::where('meet_date', '<', \date('Y-m-d H:m:s'))->orderBy('meet_date', 'desc')->take(5)->get();

        $return = [];
        foreach ($recentMeets as $meet) {
            $return[] = [
                'link' => URL::route('frontend.meet.live', ['id' => $meet->id]),
                'name' => $meet->name,
                'location' => $meet->stadium,
                'datetime' =>$meet->meet_date->format('l, F d, Y, g:ia'),
            ];
        }
        $data['recentMeets'] = $return;

        $upcomingMeets = Meet::where('meet_date', '>', \date('Y-m-d H:m:s'))->orderBy('meet_date', 'asc')->take(5)->get();

        $return = [];
        foreach ($upcomingMeets as $meet) {
            $return[] = [
                'link' => URL::route('frontend.meet.live', ['id' => $meet->id]),
                'name' => $meet->name,
                'location' => $meet->stadium,
                'datetime' =>$meet->meet_date->format('l, F d, Y, g:ia'),
            ];
        }

        $data['upcomingMeets'] = $return;
        return view('frontend.index', $data);
    }

    /**
     * @return View
     */
    public function macros()
    {
        return view('frontend.macros');
    }

    /**
     * @return View
     */
    public function about()
    {
        return view('frontend.about');
    }

    /**
     * @return View
     */
    public function requirements()
    {
        return view('frontend.requirements');
    }

    /**
     * @return View
     */
    public function contact()
    {
        return view('frontend.contact');
    }
}
