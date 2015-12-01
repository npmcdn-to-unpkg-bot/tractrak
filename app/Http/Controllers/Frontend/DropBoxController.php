<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use App\Models\Meet;
use App\Models\Race;
use App\Models\RaceType;
use App\Models\Team;
use Dropbox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;
use Input;
use Mockery\Exception\RuntimeException;
use URL;
use Ddeboer\DataImport\Reader\CsvReader;
use Session;
use Socialite;

/**
 * Class DropboxController
 */
class DropBoxController extends Controller {

    /**
     * This is the incoming notification from DropBox that something happened
     */
	public function notify(Request $request)
	{
		Log::debug($request);


	}

}
