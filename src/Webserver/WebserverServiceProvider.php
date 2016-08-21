<?php

namespace Boparaiamrit\Webserver;


use Boparaiamrit\Tenancy\Contracts\CertificateRepositoryContract;
use Boparaiamrit\Tenancy\Contracts\HostRepositoryContract;
use Illuminate\Contracts\Foundation\Application;
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
		$this->app->bind('boparaiamrit.webserver.command.toolbox', function ($app) {
			/** @var Application $app */
			return new Commands\ToolboxCommand($app->make(HostRepositoryContract::class));
		});
		
		$this->commands(['boparaiamrit.webserver.command.toolbox']);
	}
	
	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [
			'boparaiamrit.webserver.command.toolbox',
			CertificateRepositoryContract::class,
		];
	}
}
