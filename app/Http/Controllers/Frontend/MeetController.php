<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Meet;
use Input;
use Ddeboer\DataImport\Reader\CsvReader;

/**
 * Class MeetController
 * @package App\Http\Controllers\Frontend
 */
class MeetController extends Controller {

	/**
	 * @return mixed
	 */
	public function run($id)
	{
        $meet = \App\Models\Meet::find($id);
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
     * @return mixed
     */
    public function preLoad($id)
    {
        //Load file
        $file = Input::file('file');
        $fileExtension = $file->getClientOriginalExtension();
        if (strstr($fileExtension, ['ppl', 'evt', 'sch']) === false) {
            dd('That is not a valid file extension.');
        }

        switch ($fileExtension) {
            case 'ppl': $this->pplFile($id, $file); break;
            case 'evt': $this->evtFile($id, $file); break;
            case 'sch': $this->schFile($id, $file); break;
        }

        return view('frontend.meet.run')
            ->withUser(auth()->user());
    }

    private function pplFile($id, $file)
    {
        //$file = new \SplFileObject($file);
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
        $athlete = \App\Models\Athlete::where(['firstname' => $row[2], 'lastname' => $row[1], 'gender' => $gender])->firstOrCreate();

        $team = \App\Models\Team::where(['name' => $row[3]])->firstOrCreate();
    }

    }

    private function evtFile($file)
    {

    }

    private function schFile($file)
    {

    }

}