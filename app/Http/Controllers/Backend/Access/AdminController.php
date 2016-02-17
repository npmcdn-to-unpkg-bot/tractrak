<?php namespace App\Http\Controllers\Backend\Access;

use App\Http\Controllers\Controller;
use App\Models\Team;
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
        $team = Team::firstOrFail($id);

        return view('backend.edit.teamList')
            ->with([
                'team' => $team,
            ]);
    }
}
