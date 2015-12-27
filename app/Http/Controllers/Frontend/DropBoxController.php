<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Dropbox;
use Illuminate\Http\Request;
use Input;
use Log;
use Pusher;
use Session;
use Socialite;
use URL;

/**
 * Class DropboxController
 */
class DropBoxController extends Controller {

    /**
     * This is the incoming notification from DropBox that something happened
     */
	public function notify(Request $request)
	{
        $dropboxSign = $request->header('X-Dropbox-Signature');
        if ($dropboxSign !== hash_hmac('sha256', '', env('DROPBOX_KEY'))) {
            throw new \RuntimeException('Attempted Dropbox API access did not validate signature.');
        }

        // For Challenge setup
//        $challenge = Input::get('challenge');
//        echo $challenge;
//        exit();

		Log::debug($request);


        // TODO: Fix this
        $meetId = 1;

        // TODO: Can the data be included in the message?
        $message = 'update';

		Pusher::trigger("meet-$meetId", 'update-event', ['message' => $message]);
	}

}
