<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use App\Models\Meet;
use App\Models\Team;
use App\Models\Event;
use Input;
use URL;
use Ddeboer\DataImport\Reader\CsvReader;

/**
 * Class MeetController
 * @package App\Http\Controllers\Frontend
 */
class MeetController extends Controller {

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
	 * @return mixed
	 */
	public function modify()
	{
		return view('frontend.meet.modify')
				->withUser(auth()->user());
	}

    /**
     * @return mixed
     */
    public function create()
    {
        return view('frontend.meet.create')
            ->withUser(auth()->user());
    }

    /**
     * @return mixed
     */
    public function actuallyCreate()
    {
        $meet = new Meet();
        $meet->name = Input::get('meetName');
        $meet->sponsor = Input::get('meetSponsor');
        $meet->location = Input::get('meetLocation');
        $meet->meet_date = Input::get('meetDate') . ' ' . Input::get('meetTime');
        $meet->setOwner(auth()->user());
        $meet->save();

        return view('frontend.meet.modify')
            ->withUser(auth()->user());
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

        switch ($fileExtension) {
            case 'ppl': $this->pplFile($id, $file); break;
            case 'evt': $this->evtFile($id, $file); break;
            case 'sch': $this->schFile($id, $file); break;
        }

        return back()
            ->withUser(auth()->user());
    }

    /**
     * @param $id
     * @param $file
     */
    private function pplFile($id, $file)
    {
        $localFile = uniqid();
        $file->move('/tmp', $localFile);
        $file = new \SplFileObject("/tmp/$localFile");
        $reader = new CsvReader($file);

        foreach ($reader as $row) {
        // $row will be an array containing the comma-separated elements of the line:
        // array(
        //   0 => AthleteId,
        //   1 => LastName
        //   2 => FirstName
        //   3 => Team
        //   4 => ?
        //   5 => Gender
        //   6 => Events (one is int, two is csv list in quotes ")
        // )

            $gender = $row[5] === 'M' ? 0 : 1;
            /** @var Athlete $athlete */
            $athlete = Athlete::where(['firstname' => $row[2], 'lastname' => $row[1], 'gender' => $gender])->firstOrCreate();

            /** @var Team $team */
            $team = Team::where(['name' => $row[3]])->firstOrCreate();

            $athlete->teams()->attach([$team->id => ['current' => true]]);
        }
        unset($localFile);
    }

    /**
     * @param $id
     * @param $file
     */
    private function evtFile($id, $file)
    {
        $localFile = uniqid();
        $file->move('/tmp', $localFile);
        $file = new \SplFileObject("/tmp/$localFile");
        $reader = new CsvReader($file);

        foreach ($reader as $row) {
            // Two types of rows depending on first value
            // 0 = event
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
                $gender = is_int(\strpos($row[4], 'Boys')) ? 0 : 1;
                $team = \strpos($row[4], 'Relay') !== false && \strpos($row[4], 'Medley') !== false ? 1 : 0;
                /** @var Event $event */
                $event = Event::firstOrCreate(['name' => $row[3], 'gender' => $gender, 'athlete_team' => $team]);

                $event->meets()->attach([$id => ['event' => $row[0], 'round' => $row[1], 'heat' => $row[2]]]);
            } else {
                // If 4 is empty, this is a team
                if (empty($row[4])) {
                    /** @var Team $team */
                    $team = Team::firstOrCreate(['name' => $row[3]]);
                    $team->abbr = substr($row[5], 0, 4);
                    $team->save();
                    $team->events()->attach([$event->id => ['lane' => $row[2]]]);
                } else {
                    /** @var Athlete $athlete */
                    $athlete = Athlete::firstOrCreate(['firstname' => $row[4], 'lastname' => $row[3], 'gender' => $gender]);

                    /** @var Team $team */
                    $team = Team::firstOrCreate(['name' => $row[5]]);

                    $athlete->teams()->attach([$team->id => ['current' => true]]);
                    $athlete->events()->attach([$event->id => ['lane' => $row[2]]]);
                }
            }
        }
        unset($localFile);
    }

    /**
     * @param $id
     * @param $file
     */
    private function schFile($id, $file)
    {
        $localFile = uniqid();
        $file->move('/tmp', $localFile);
        $file = new \SplFileObject("/tmp/$localFile");
        $reader = new CsvReader($file);

        unset($localFile);
    }
}