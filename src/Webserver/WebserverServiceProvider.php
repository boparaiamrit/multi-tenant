<?php

namespace Boparaiamrit\Webserver;


use Illuminate\Support\ServiceProvider;

class WebserverServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;
	
	public function boot()
	{
		// configuration
		$this->mergeConfigFrom(__DIR__ . '/../../config/webserver.php', 'webserver');
		
		// adds views
		$this->loadViewsFrom(__DIR__ . '/../../views/webserver', 'webserver');
	}
	
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		/*
		 * Toolbox command
		 */
		$this->app->bind('webserver', function () {
			return new Commands\WebserverCommand();
		});
		
		$this->commands(Commands\WebserverCommand::class);
	}
	
	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [
			'webserver'
		];
	}
}
