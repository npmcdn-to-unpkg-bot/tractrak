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
        $currentMeets = Meet::whereBetween('meet_date', [\date('Y-m-d 00:00:00'), \date('Y-m-d 23:59:59')])->where('paid', 1)->orderBy('meet_date', 'desc')->get();
        $data['currentMeets'] = $currentMeets;
        
        $recentMeets = Meet::where('meet_date', '<', \date('Y-m-d H:m:s'))->where('paid', 1)->whereNotIn('id', $currentMeets->keys()->toArray())->orderBy('meet_date', 'desc')->take(5)->get();
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
