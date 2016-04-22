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
            Log::debug($delta);

            foreach ($delta['entries'] as $dropboxFile) {
                if ($dropboxFile[1] === null || strpos($dropboxFile[0], '.') === false) {
                    //The file was deleted, so ignore
//                    Log::debug($dropboxFile[0] . ' was deleted.');
                    //This is a directory
                    Log::debug('Dropbox file:' . print_r($dropboxFile, true));
                    continue;
                }

                //ignore everything but LIF
                if (strpos($dropboxFile[0], '.lif') === false) {
                    Log::debug('Ignoring Dropbox file for no .lif:' . $dropboxFile[0]);
                    continue;
                }

                $filename = storage_path('meets') . $dropboxFile[0];
                $dirname = dirname($filename);
                if (!is_dir($dirname)) {
                    Log::debug($filename);
                    Log::debug($dirname);
                    mkdir($dirname, 0750, true);
                }

//                Log::debug('Dropbox file:' . $dropboxFile[0]);
//                Log::debug('storage path:' . storage_path('meets'));
//                Log::debug('filename:'.$filename);
                $fd = fopen($filename, "wb");
                $dropboxUser->getFile($dropboxFile[0], $fd);

                // process the file for data
                $eventRoundHeat = $this->lifFile($filename, $meetId);
                if (empty($eventRoundHeat)) {
                    continue;
                }

                fclose($fd);

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
     */
    public function challenge(Request $request)
    {
        // For Challenge setup
        $challenge = Input::get('challenge');
        echo $challenge;
    }

    /**
     * @param $file
     * @return array
     */
    private function lifFile($file, $meetId)
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
                        'meet_id' => $meetId,
                        'event' => $eventId,
                        'round' => $roundId,
                        'heat' => $heatId,
                    ])->firstOrFail();

                    if (!empty($row[10])) {
                        $race->start = $row[10];
                    }
                    $race->save();
                    Log::debug("Row 1 processed for {$row[3]}: $eventId|$roundId|$heatId");
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

                        $lane = $race->teams()->find($team->id);
//                        Log::debug($race->teams());
//                        Log::debug('Lane');
//                        Log::debug($lane);
//                        Log::debug('Pivot');
//                        Log::debug($lane->pivot);

                        if (isset($row[0])) {
                            $lane->pivot->place = $row[0];
                        };
                        if (isset($row[6])) {
                            $lane->pivot->result = $row[6];
                        }

                        $updated = $lane->pivot->save();

//                        Log::debug('Pivot after update:');
//                        Log::debug($lane->pivot);
//                        $updated = $race->teams()->updateExistingPivot($team->id, ['lane' => $row[2], 'result' => $row[6], 'place' => $place], false);

                        if ($updated) {
                            Log::debug("Row successfully processed for team, lane {$row[2]}, {$row[3]}: {$row[0]}: {$row[6]}");
                        } else {
                            Log::error("Row NOT processed for team, lane {$row[2]}, {$row[3]}: {$row[0]}: {$row[6]}");
                            Log::debug($race->teams()->getResults());
                        }
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

                        if (!empty($row[12])) {
                            $gender = $row[12] === 'M' ? 0 : 1;
                            $athlete = Athlete::where(['firstname' => $row[4], 'lastname' => $row[3], 'gender' => $gender])->firstOrFail();
                        } else {
                            $athlete = Athlete::where(['firstname' => $row[4], 'lastname' => $row[3]])->firstOrFail();
                        }
                        /** @var Athlete $athlete */

                        $lane = $race->athletes()->find($athlete->id);

                        if (isset($row[0])) {
                            $lane->pivot->place = $row[0];
                        };
                        if (isset($row[6])) {
                            $lane->pivot->result = $row[6];
                        }

//                        Log::debug($race->teams());
//                        Log::debug('Lane');
//                        Log::debug($lane);
//                        Log::debug('Pivot');
//                        Log::debug($lane->pivot);

                        $updated = $lane->pivot->save();

//                        Log::debug('Pivot after update:');
//                        Log::debug($lane->pivot);
//                        $updated = $race->athletes()->updateExistingPivot($athlete->id, ['lane' => $row[2], 'result' => $result, 'place' => $place], false);

                        if ($updated) {
                            Log::debug("$updated row successfully processed for athlete, lane {$row[2]}, {$row[4]} {$row[3]}: {$row[0]}: {$row[6]}");
                        } else {
                            Log::debug("$updated Row NOT processed for athlete, lane {$row[2]}, {$row[4]} {$row[3]}: {$row[0]}: {$row[6]}");
                            Log::debug($race->athletes()->getResults());
                        }
                    }
                }
            }

            unset($localFile);
        } catch (ModelNotFoundException $e) {
            Log::debug($e);
        }

        if (empty($eventId) || empty($roundId) || empty($heatId)) {
            Log::debug("Did not have event or round or heat: $eventId|$roundId|$heatId");
            return [];
        }
        Log::debug("Successfully processed event|round|heat: $eventId|$roundId|$heatId");

        return ['event' => $eventId, 'round' => $roundId, 'heat' => $heatId];
    }
}
