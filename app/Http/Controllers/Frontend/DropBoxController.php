<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Access\User\User;
use App\Models\Athlete;
use App\Models\Race;
use App\Models\Team;
use Ddeboer\DataImport\Reader\CsvReader;
use Dropbox;
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
                $metadata = $dropboxUser->getFile($dropboxFile[0], $fd);

                // process the file for data
                $this->lifFile($filename);

                fclose($fd);

                // send notice now
                // TODO: Can the data be included in the message?
                $data = 'update';

                LaravelPusher::trigger(["meet-$meetId"], 'update', ['data' => $data]);
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
            } else {
                // $row will be an array containing the comma-separated elements of the line:
                // array(
                //   0 => place,
                //   1 => athleteId,
                //   2 => LastName
                //   3 => FirstName
                //   4 => Team
                //   5 => time
                //   5 => ?
                //   6 => delta time from previous position
                //   7 => ?
                //   8 => ?
                //   9 => time of day (local) started
                //   10 => gender?
                //   11 => Races (one is int, two is csv list in quotes ")
                //   12 => ?
                //   13 => delta time from previous position?
                //   13 => delta time from previous position?
                // )

                $gender = $row[10] === 'M' ? 0 : 1;
                /** @var Athlete $athlete */
                $athlete = Athlete::firstOrCreate(['firstname' => $row[3], 'lastname' => $row[2], 'gender' => $gender]);

                /** @var Team $team */
                $team = Team::firstOrCreate(['name' => $row[4]]);

                $athlete->teams()->attach([$team->id => ['current' => true]]);

                $race = Race::where()->first();
            }
        }
        unset($localFile);
    }

}
