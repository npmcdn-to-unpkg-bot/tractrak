<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use App\Models\State;
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
}
