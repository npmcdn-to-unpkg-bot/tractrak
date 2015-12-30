<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Access\User\User;
use App\Models\Athlete;
use App\Models\Race;
use App\Models\Team;
use Ddeboer\DataImport\Reader\CsvReader;
use Dropbox;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Input;
use Log;
use LaravelPusher;
use Session;
use Socialite;
use URL;

/**
 * Class DropboxController
 */
class DropBoxController extends Controller
{

    /**
     * This is the incoming notification from DropBox that something happened
     * @param Request $request
     */
    public function notify(Request $request)
    {
//        $dropboxSign = $request->header('X-Dropbox-Signature');
//        if ($dropboxSign !== hash_hmac('sha256', '', env('DROPBOX_KEY'))) {
//            throw new \RuntimeException('Attempted Dropbox API access did not validate signature.');
//        }

//		Log::debug($request);

        $delta = Input::get('delta');
        $userIds = $delta['users'];
        foreach ($userIds as $userId) {
            // Find the User and the ActiveMeet
            $user = User::where(['dropboxId' => $userId])->firstOrFail();
            $meetId = $user->activeMeet;
            $cursor = $user->cursor;

//            Log::debug('User accessToken: ' . $user->accessToken);
            // Get the Delta from Dropbox
            $dropboxUser = new Dropbox\Client($user->accessToken, 'TracTrak/0.1');

            $delta = $dropboxUser->getDelta($cursor);
//            Log::debug('User delta:');
            Log::debug($delta);

            foreach ($delta['entries'] as $dropboxFile) {
                if ($dropboxFile[1] === null) {
                    //The file was deleted, so ignore
//                    Log::debug($dropboxFile[0] . ' was deleted.');
                    continue;
                }
                $filename = storage_path() . $dropboxFile[0];
                $dirname = dirname($filename);
                if (!is_dir($dirname)) {
                    mkdir($dirname, 0750, true);
                }

                $fd = fopen($filename, "wb");
                $dropboxUser->getFile($dropboxFile[0], $fd);

                // process the file for data
                $eventId = $this->lifFile($filename);

                fclose($fd);

                // send notice now
                // TODO: Can the data be included in the message?
                $data = 'update';

                LaravelPusher::trigger(["meet-$meetId"], 'update', ['data' => ['event' => $eventId]]);
            }

            $newCursor = $delta['cursor'];
//            Log::debug($newCursor);

            $user->cursor = $newCursor;
            $user->save();
        }
    }

    /**
     * This is the challenge setup
     */
    public function challenge(Request $request)
    {
        // For Challenge setup
        $challenge = Input::get('challenge');
        echo $challenge;
        exit();
    }

    /**
     * @param $file
     */
    private function lifFile($file)
    {
        $file = new \SplFileObject($file);
        $reader = new CsvReader($file);
        $rowCounter = 0;
        try {
            foreach ($reader as $row) {
                ++$rowCounter;

                if ($rowCounter === 1) {
                    // $row will be an array containing the comma-separated elements of the line:
                    // array(
                    //   0 => eventId,
                    //   1 => round
                    //   2 => heat
                    //   3 => name
                    //   4 =>
                    //   9 => distance?
                    //   10 => Time of day (local) started
                    // )
                    $eventId = $row[0];
                    $roundId = $row[1];
                    $heatId = $row[2];

                    /** @var Race $race */
                    $race = Race::where([
                        'event' => $eventId,
                        'round' => $roundId,
                        'heat' => $heatId,
                    ])->firstOrFail();

                    $race->start = $row[10];
                    $race->save();
                } else {
                    if (empty($row[1])) {
                        // Team event
                        // $row will be an array containing the comma-separated elements of the line:
                        // array(
                        //   0 => place
                        //   1 => (blank) - this implies it's a team event
                        //   2 => lane
                        //   3 => Team name
                        //   4 => ?
                        //   5 => team abbreviation
                        //   6 => time
                        //   7 => ?
                        //   8 => delta time from previous position
                        //   9 => ?
                        //   10 => ?
                        //   11 => time of day (local) started
                        //   12 => ?
                        //   13 => ?
                        //   14 => ?
                        //   15 => delta time from previous position?
                        //   16 => delta time from previous position?
                        // )

                        /** @var Team $team */
                        $team = Team::where(['name' => $row[3]])->firstOrFail();

                        $race->teams()->updateExistingPivot($team->id, ['lane' => $row[2], 'result' => $row[6], 'place' => $row[0]]);
                    } else {
                        // Athlete event
                        // $row will be an array containing the comma-separated elements of the line:
                        // array(
                        //   0 => place
                        //   1 => athleteId
                        //   2 => lane
                        //   3 => LastName
                        //   4 => FirstName
                        //   5 => Team
                        //   6 => time
                        //   7 => ?
                        //   8 => delta time from previous position
                        //   9 => ?
                        //   10 => ?
                        //   11 => time of day (local) started
                        //   12 => gender?
                        //   13 => Races (one is int, two is csv list in quotes ")
                        //   14 => ?
                        //   15 => delta time from previous position?
                        //   16 => delta time from previous position?
                        // )

                        $gender = $row[12] === 'M' ? 0 : 1;
                        /** @var Athlete $athlete */
                        $athlete = Athlete::where(['firstname' => $row[4], 'lastname' => $row[3], 'gender' => $gender])->firstOrFail();

                        $race->athletes()->updateExistingPivot($athlete->id, ['lane' => $row[2], 'result' => $row[6], 'place' => $row[0]]);
                    }
                }
            }
            unset($localFile);
        }
    catch (ModelNotFoundException $e) {
Log::debug($e);
}
        return $eventId;
}

}