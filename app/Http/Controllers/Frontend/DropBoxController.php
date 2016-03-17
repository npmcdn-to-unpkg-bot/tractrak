<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Access\User\User;
use App\Models\Athlete;
use App\Models\Meet;
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
            $dropboxUser = new Dropbox\Client($user->accessToken, 'TracTrak/0.2');

            $delta = $dropboxUser->getDelta($cursor);
//            Log::debug('User delta:');
//            Log::debug($delta);

            foreach ($delta['entries'] as $dropboxFile) {
                if ($dropboxFile[1] === null || $dropboxFile[1]['is_dir'] === true || strpos($dropboxFile[0], '.lif') === false) {
                    //The file was deleted, so ignore
                    //This is a directory, so ignore
                    //This is not a ".lif" file, so ignore
                    continue;
                }
                $filename = storage_path('meets') . $dropboxFile[0];
//                Log::debug('Filename');
//                Log::debug($filename);
                $directoryName = dirname($filename);
                if (!is_dir($directoryName)) {
                    mkdir($directoryName, 0750, true);
                }

                $fd = fopen($filename, "wb");
                $dropboxUser->getFile($dropboxFile[0], $fd);

                // process the file for data
                $eventRoundHeat = $this->lifFile($filename, $meetId);

                fclose($fd);
                // TODO: Delete the file

                // if the lif process returned null, it was a bad process
                if ($eventRoundHeat === null) {
                    continue;
                }

                // send notice now
                // TODO: Can the data be included in the message?
                $data = (new MeetController())->event($request, $meetId, $eventRoundHeat['event'], $eventRoundHeat['round']);

                LaravelPusher::trigger(["meet-$meetId"], 'update', ['data' => [
                    'event' => $eventRoundHeat['event'],
                    'round' => $eventRoundHeat['round'],
                    'heat' => $eventRoundHeat['heat'],
                    'data' => $data,
                ]]);
            }

            $newCursor = $delta['cursor'];
//            Log::debug($newCursor);

            $user->cursor = $newCursor;
            $user->save();
        }
    }

    /**
     * This is the challenge setup
     * @param Request $request
     */
    public function challenge(Request $request)
    {
        // For Challenge setup
        $challenge = Input::get('challenge');
        echo $challenge;
    }

    /**
     * @param $file
     * @param int $meetID
     * @return array
     */
    private function lifFile($file, $meetID)
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
                        'meet_id' => $meetID,
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

                        $place = isset($row[0]) ? $row[0] : null;
                        $race->teams()->updateExistingPivot($team->id, ['lane' => $row[2], 'result' => $row[6], 'place' => $place]);
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

                        $place = isset($row[0]) ? $row[0] : null;
                        $race->athletes()->updateExistingPivot($athlete->id, ['lane' => $row[2], 'result' => $row[6], 'place' => $place]);
                    }
                }
            }
            unset($localFile);
        } catch (ModelNotFoundException $e) {
            Log::debug($e);
        }

        if ($eventId && $roundId && $heatId) {
            return ['event' => $eventId, 'round' => $roundId, 'heat' => $heatId];
        }

        return;
    }
}
