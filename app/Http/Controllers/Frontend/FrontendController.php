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
        $recentMeets = Meet::where('meet_date', '<', \date('Y-m-d H:m:s'))->where('paid', 1)->orderBy('meet_date', 'desc')->take(5)->get();
        $data['recentMeets'] = $recentMeets;

        $upcomingMeets = Meet::where('meet_date', '>', \date('Y-m-d H:m:s'))->where('paid', 1)->orderBy('meet_date', 'asc')->take(5)->get();
        $data['upcomingMeets'] = $upcomingMeets;

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
