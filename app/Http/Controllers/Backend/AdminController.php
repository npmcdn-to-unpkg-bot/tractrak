<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use App\Models\Stadium;
use App\Models\Team;
use Illuminate\Support\Facades\Input;
use Illuminate\View\View;

/**
 * Class BackendController
 * @package App\Http\Controllers
 */
class AdminController extends Controller
{
    /**
     * @return View
     */
    public function selectTeamToEdit()
    {
        return view('backend.edit.teamList')
            ->with([
                'teams' => Team::orderBy('name', 'asc')->get(),
            ]);
    }

    /**
     * @param integer $id
     * @return View
     */
    public function editTeam($id)
    {
        $team = Team::find($id);

        return view('backend.edit.team')
            ->with([
                'team' => $team,
            ]);
    }

    /**
     * @param integer $id
     * @return View
     */
    public function saveEditTeam($id)
    {
        $team = Team::find($id);

        $team->name = Input::get('name');
        $team->abbr = Input::get('abbr');
        $team->stateid = Input::get('stateid');
        $team->countryid = Input::get('countryid');

        $team->save();

        return back()->with([
            'status' => 'Meet updated!',
        ]);
    }

    /**
     * @return View
     */
    public function selectAthleteToEdit()
    {
        return view('backend.edit.athleteList')
            ->with([
                'athletes' => Athlete::orderBy('lastname', 'asc')->get(),
            ]);
    }

    /**
     * @param integer $id
     * @return View
     */
    public function editAthlete($id)
    {
        $athlete = Athlete::find($id);

        return view('backend.edit.athlete')
            ->with([
                'athlete' => $athlete,
            ]);
    }

    /**
     * @return View
     */
    public function createAthlete()
    {
        $athlete = new Athlete;

        return view('backend.edit.athlete')
            ->with([
                'athlete' => $athlete,
            ]);
    }

    /**
     * @param integer $id
     * @return View
     */
    public function saveEditAthlete($id)
    {
        $athlete = Athlete::find($id);

        $athlete->firstname = Input::get('firstname');
        $athlete->lastname = Input::get('lastname');
        $athlete->gender = Input::get('gender');

        if (Input::get('height')) {
            $athlete->height = Input::get('height');
        }

        if (Input::get('weight')) {
            $athlete->weight = Input::get('weight');
        }

        $athlete->save();

        return back()->with([
            'status' => 'Athlete updated!',
        ]);
    }

    /**
     * @return View
     */
    public function selectStadiumToEdit()
    {
        return view('backend.edit.stadiumList')
            ->with([
                'stadiums' => Stadium::orderBy('name', 'asc')->get(),
            ]);
    }

    /**
     * @param integer $id
     * @return View
     */
    public function editStadium($id)
    {
        $stadium = Stadium::find($id);

        return view('backend.edit.stadium')
            ->with([
                'stadium' => $stadium,
            ]);
    }

    /**
     * @param integer $id
     * @return View
     */
    public function saveEditStadium($id)
    {
        $stadium = Stadium::find($id);

        $stadium->name = Input::get('name');
        $stadium->googlename = Input::get('googlename');
        $stadium->city = Input::get('city');
        $stadium->stateid = Input::get('stateid');
        $stadium->zip = Input::get('zip');
        $stadium->countryid = Input::get('countryid');

        if (Input::get('address')) {
            $stadium->address = Input::get('address');
        }

        if (Input::get('lat')) {
            $stadium->lat = Input::get('lat');
        }

        if (Input::get('lng')) {
            $stadium->lng = Input::get('lng');
        }

        $stadium->save();

        return back()->with([
            'status' => 'Stadium updated!',
        ]);
    }
}
