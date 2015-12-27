<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

class LaravelLoggerProxy {
	public function log( $msg ) {
		Log::info($msg);
	}
}

/**
 * Class AppServiceProvider
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
        $pusher = $this->app->make('pusher');
        $pusher->set_logger( new LaravelLoggerProxy() );
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		if ($this->app->environment() == 'local') {
			$this->app->register(\Laracasts\Generators\GeneratorsServiceProvider::class);
		}
	}
}
