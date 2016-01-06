<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use App\Models\Meet;
use App\Models\Race;
use App\Models\RaceType;
use App\Models\Stadium;
use App\Models\Team;
use Dropbox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Input;
use Mockery\Exception\RuntimeException;
use Ddeboer\DataImport\Reader\CsvReader;
use Socialite;

/**
 * Class MeetController
 * @package App\Http\Controllers\Frontend
 */
class MeetController extends Controller
{
    /**
     * @param $id
     * @return mixed
     */
    public function run($id)
    {
        $meet = Meet::findOrFail($id);
        return view('frontend.meet.run')
            ->withUser(auth()->user())
            ->with(['meet' => $meet]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        $stadiumResults = Stadium::all();
        $stadiums = [];
        foreach ($stadiumResults as $key => $value) {
            $stadiums[$key] = $value->name;
        }

        $meet = Meet::findOrFail($id);
        return view('frontend.meet.modify')
            ->withUser(auth()->user())
            ->with([
                'meet' => $meet,
                'stadiums' => $stadiums,
            ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function actuallyEdit($id)
    {
        $meet = Meet::findOrFail($id);
        $meet->name = Input::get('meetName');
        $meet->sponsor = Input::get('meetSponsor');
        $meet->stadium = Input::get('stadium');
        $meet->meet_date = Input::get('meetDate') . ' ' . Input::get('meetTime');
        $meet->save();

        return view('frontend.meet.modify')
            ->withUser(auth()->user())
            ->with(['meet' => $meet]);
    }

    /**
     * @return mixed
     */
    public function create()
    {
        $stadiumResults = Stadium::all();
        $stadiums = [];
        foreach ($stadiumResults as $key => $value) {
            $stadiums[$key] = $value->name;
        }

        return view('frontend.meet.create')
            ->withUser(auth()->user())
            ->with([
                'stadiums' => $stadiums,
            ]);
    }

    /**
     * @return mixed
     */
    public function actuallyCreate()
    {
        $meet = new Meet();
        $meet->name = Input::get('meetName');
        $meet->sponsor = Input::get('meetSponsor');
        $meet->stadium = Input::get('stadium');
        $meet->meet_date = Input::get('meetDate') . ' ' . Input::get('meetTime') . ':00';
        $meet->setOwner(auth()->user());
        $meet->save();

        return view('frontend.meet.modify')
            ->withUser(auth()->user())
            ->with(['meet' => $meet]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function preLoad($id)
    {
        //Load file
        $file = Input::file('file');
        $fileExtension = $file->getClientOriginalExtension();
        if (\in_array($fileExtension, ['ppl', 'evt', 'sch']) === false) {
            dd('That is not a valid file extension.');
        }

        $meet = Meet::find($id);

        switch ($fileExtension) {
            case 'ppl':
                $this->pplFile($file);
                $meet->ppl = true;
                $meet->save();
                break;
            case 'evt':
                $this->evtFile($id, $file);
                $meet->evt = true;
                $meet->save();
                break;
            case 'sch':
                $this->schFile($meet, $file);
                $meet->sch = true;
                $meet->save();
                break;
        }

        return back()
            ->withUser(auth()->user());
    }

    /**
     * @param $file
     */
    private function pplFile($file)
    {
        $localFile = uniqid();
        $file->move('/tmp', $localFile);
        $file = new \SplFileObject("/tmp/$localFile");
        $reader = new CsvReader($file);

        foreach ($reader as $row) {
            if (!isset($row[1])) {
                continue;
            }
            // $row will be an array containing the comma-separated elements of the line:
            // array(
            //   0 => AthleteId,
            //   1 => LastName
            //   2 => FirstName
            //   3 => Team
            //   4 => ?
            //   5 => Gender
            //   6 => Races (one is int, two is csv list in quotes ")
            // )

            $gender = $row[5] === 'M' ? 0 : 1;
            /** @var Athlete $athlete */
            $athlete = Athlete::firstOrCreate(['firstname' => $row[2], 'lastname' => $row[1], 'gender' => $gender]);

            /** @var Team $team */
            $team = Team::firstOrCreate(['name' => $row[3]]);

            $athlete->teams()->attach([$team->id => ['current' => true]]);
        }
        unset($localFile);
    }

    /**
     * @param integer $id
     * @param $file
     * @throws RuntimeException
     */
    private function evtFile($id, $file)
    {
        $localFile = uniqid();
        $file->move('/tmp', $localFile);
        $file = new \SplFileObject("/tmp/$localFile");
        $reader = new CsvReader($file);

        foreach ($reader as $row) {
            // Two types of rows depending on first value
            // 0 = Race
            // 1 = round
            // 2 = heat
            // 3 = Event Name
            // 4 = ?
            //
            // 9 = Distance in m

            // 0 = blank
            // 1 = athlete ID (local)
            // 2 = lane assignment
            // 3 = LastName
            // 4 = FirstName
            // 5 = TeamName
            if (!empty($row[0])) {
                $gender = is_int(\strpos($row[3], 'Boys')) ? 0 : 1;
                $team = \strpos($row[3], 'Relay') !== false || \strpos($row[3], 'Medley') !== false ? 1 : 0;
                $raceType = RaceType::firstOrCreate(['name' => $row[3], 'gender' => $gender, 'athlete_team' => $team]);

                /** @var Race $race */
                $race = Race::firstOrCreate([
                    'meet_id' => $id,
                    'race_type' => $raceType->id,
                    'event' => $row[0],
                    'round' => $row[1],
                    'heat' => $row[2],
                ]);
            } else {
                if (!is_object($race)) {
                    throw new RuntimeException('The file is not formatted properly.');
                }
                // If 4 is empty, this is a team
                if (empty($row[4])) {
                    // TODO Need to find teams better, filtering by state at least
                    /** @var Team $team */
                    $team = Team::firstOrCreate(['name' => $row[3]]);
                    $team->abbr = substr($row[5], 0, 4);
                    $team->save();
                    if ($team->id === 0) {
                        throw new RuntimeException('Team id is zero: ' . $team->name);
                    }
                    $race->teams()->attach([$team->id => ['lane' => $row[2]]]);
                } else {
                    /** @var Athlete $athlete */
                    $athlete = Athlete::firstOrCreate(['firstname' => $row[4], 'lastname' => $row[3], 'gender' => $gender]);

                    /** @var Team $team */
                    $team = Team::firstOrCreate(['name' => $row[5]]);
                    $athlete->teams()->attach([$team->id => ['current' => true]]);

                    $race->athletes()->attach([$athlete->id => ['lane' => $row[2]]]);
                }
            }
        }
        unset($localFile);
    }

    /**
     * @param Meet $meet
     * @param $file
     */
    private function schFile($meet, $file)
    {
        $localFile = uniqid();
        $file->move('/tmp', $localFile);
        $file = new \SplFileObject("/tmp/$localFile");
        $reader = new CsvReader($file);
        $order = 0;
        foreach ($reader as $row) {
            //Handles comment lines, like the very first line
            if (!isset($row[1])) {
                continue;
            }
            // 0 = event
            // 1 = round
            // 2 = heat
            ++$order;

            Race::where([
                'meet_id' => $meet->id,
                'event' => $row[0],
                'round' => $row[1],
                'heat' => $row[2],
            ])->update(['schedule' => $order]);
        }
        unset($localFile);
    }

    /**
     * @param Request $request
     * @param integer $id
     * @return mixed
     */
    protected function dropboxStart(Request $request, $id)
    {
        $request->session()->flash('meetId', $id);
        return Socialite::with('dropbox')->redirect();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    protected function dropboxFinish(Request $request)
    {
        $dropboxUser = Socialite::with('dropbox')->user();
        $token = $dropboxUser->token;

        $user = auth()->user();
        $user->accessToken = $token;
        $user->save();

        $meetId = $request->session()->pull('meetId');
        return redirect()->route('frontend.meet.run', [$meetId]);
    }

    public function live($id)
    {
        $meet = Meet::findOrFail($id);

        return view('frontend.meet.live')
            ->withUser(auth()->user())
            ->with(['meet' => $meet]);
    }

    /**
     * TODO Tune/eager load queries, since this will get hit, A LOT
     * @param integer $meetId
     * @param integer $eventId
     * @param integer $roundId
     * @param integer $heatId
     * @return array (This Laravel automatically converts to JSON)
     */
    public function event($meetId, $eventId, $roundId = null, $heatId = null)
    {
        $where = ['meet_id' => $meetId, 'event' => $eventId];
        if (!is_null($roundId)) {
            $where['round'] = $roundId;
        }
        if (!is_null($heatId)) {
            $where['heat'] = $heatId;
        }

        $races = Race::where($where)->with('athletes')->with('teams')->get();
        $return = [];

        /* @var Race $race */
        foreach ($races as $race) {
//            \Debugbar::info($race);
            if ($race->isAthleteRace()) {
                $athletes = $race->athletes()->get();
//                \Debugbar::info($athletes);
                foreach ($athletes as $athlete) {
                    $return[] = [
                        $race->round,
                        $race->heat,
                        $athlete->pivot->lane,
                        $athlete->lastname . ', ' . $athlete->firstname,
                        $athlete->teams()->where(['current' => 1])->first()->abbr,
                        $athlete->pivot->place,
                        !empty($athlete->pivot->result) ? $athlete->pivot->result : '&nbsp;',
                        '&nbsp;',
                    ];
                }
            } else {
                $teams = $race->teams()->get();
//                \Debugbar::info($teams);
                foreach ($teams as $team) {
                    $return[] = [
                        $race->round,
                        $race->heat,
                        $team->pivot->lane,
                        $team->name,
                        $team->abbr,
                        $team->pivot->place,
                        !empty($team->pivot->result) ? $team->pivot->result : '&nbsp;',
                        '&nbsp;',
                    ];
                }
            }
        }

        return ['data' => $return];
    }

    /**
     * TODO Tune/eager load queries, since this will get hit, A LOT
     * @param integer $meetId
     * @param integer $eventId
     * @param integer $roundId
     * @param integer $heatId
     * @return array (This Laravel automatically converts to JSON)
     */
    public function eventRoundHeat($meetId, $eventId, $roundId, $heatId)
    {
        $races = Race::where([
            'meet_id' => $meetId,
            'event' => $eventId,
            'round' => $roundId,
            'heat' => $heatId,
        ])->with('athletes')->with('teams')->get();

        $return = [];

        /* @var Race $race */
        foreach ($races as $race) {
//            \Debugbar::info($race);
            if ($race->isAthleteRace()) {
                $athletes = $race->athletes()->get();
//                \Debugbar::info($athletes);
                foreach ($athletes as $athlete) {
                    $return[] = [
                        $race->round,
                        $race->heat,
                        $athlete->pivot->lane,
                        $athlete->lastname . ', ' . $athlete->firstname,
                        $athlete->teams()->where(['current' => 1])->first()->abbr,
                        $athlete->pivot->place,
                        !empty($athlete->pivot->result) ? $athlete->pivot->result : '&nbsp;',
                        '&nbsp;',
                    ];
                }
            } else {
                $teams = $race->teams()->get();
//                \Debugbar::info($teams);
                foreach ($teams as $team) {
                    $return[] = [
                        $race->round,
                        $race->heat,
                        $team->pivot->lane,
                        $team->name,
                        $team->abbr,
                        $team->pivot->place,
                        !empty($team->pivot->result) ? $team->pivot->result : '&nbsp;',
                        '&nbsp;',
                    ];
                }
            }
        }

        return ['data' => $return];
    }
}
