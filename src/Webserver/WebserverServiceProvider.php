<?php

namespace Boparaiamrit\Webserver;


use Illuminate\Support\ServiceProvider;

class WebserverServiceProvider extends ServiceProvider
{
	const TOOLBOX_COMMAND = 'webserver.command.toolbox';
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;
	
	public function boot()
	{
		
		// configuration
		$this->mergeConfigFrom(
			__DIR__ . '/../../config/webserver.php',
			'webserver'
		);
		$this->publishes(
			[__DIR__ . '/../../config/webserver.php' => config_path('webserver.php')],
			'boparaiamrit-webserver-config'
		);
		
		// adds views
		$this->loadViewsFrom(
			__DIR__ . '/../../views/webserver',
			'webserver'
		);
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
		$this->app->bind(self::TOOLBOX_COMMAND, function () {
			return new Commands\ToolboxCommand();
		});
		
		$this->commands([self::TOOLBOX_COMMAND]);
	}
	
	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [
			self::TOOLBOX_COMMAND
		];
	}
}
