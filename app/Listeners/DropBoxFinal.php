<?php namespace App\Listeners;


use Illuminate\Queue\InteractsWithQueue;
use App\Events\Frontend\Auth\UserLoggedIn;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class DropBoxFinalHandler
 */
class DropBoxFinalHandler implements ShouldQueue {

    use InteractsWithQueue;

    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserLoggedIn  $event
     * @return void
     */
    public function handle(UserLoggedIn $event)
    {
        //TODO
    }
}
