<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Access\User\User;
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
class DropBoxController extends Controller {

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

            foreach ($delta['entires'] as $dropboxFile) {
                $fd = fopen(storage_path() . DIRECTORY_SEPARATOR , $dropboxFile[0], "wb");
                $metadata = $dropboxUser->getFile($dropboxFile[0], $fd);

                // process the file for data

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
}
