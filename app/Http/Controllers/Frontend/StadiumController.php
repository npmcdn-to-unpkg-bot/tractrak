<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Race;
use App\Models\Stadium;
use DB;


/**
 * Class StadiumController
 * @package App\Http\Controllers\Frontend
 */
class StadiumController extends Controller
{
    /**
     * Base view for a Stadium
     * @param integer $id
     * @return mixed
     */
    public function viewStadium($id)
    {
        /** @var Stadium $stadium */
        $stadium = Stadium::findOrFail($id);

        $meets = $stadium->meets()->orderBy('meet_date', 'desc')->getEager()->getDictionary();

        $records = $this->recordGenerator($stadium);

        return view('frontend.stadium.view')
            ->withUser(auth()->user())
            ->with([
                'stadium' => $stadium,
                'meets' => $meets,
                'records' => $records['records'],
                'races' => $records['races'],
            ]);
    }

    /**
     * Figures out the records for all events at this Stadium, updates Record tables
     * @param Stadium $stadium
     * @return array
     */
    private function recordGenerator(Stadium $stadium)
    {
        $meetList = $stadium->meets()->get()->lists('id');

        // Build list of events ever held at stadium
        $events = Race::whereIn('meet_id', $meetList)->get()->lists('id');

        // Get best result of each event at stadium
        $results = DB::table('competitors')
            ->leftJoin('races', 'races.id', '=', 'competitors.race_id')
            ->whereIn('competitors.race_id', $events)
            ->where('competitors.place', '1')
            ->groupBy('races.race_type')
            ->select(['competitors.race_id', 'competitors.competitor_id', 'competitors.competitor_type', 'competitors.result', 'races.meet_id', 'races.race_type'])
            ->get();

        // Iterate over each result to gather the races
        $races = [];
        foreach ($results as $result) {
            $races[] = $result->race_id;
        }

        $races = Race::find($races)->getDictionary();

        return [
            'races' => $races,
            'records' => $results,
        ];
    }
}
