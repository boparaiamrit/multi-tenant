<?php

namespace Boparaiamrit\Tenancy;


use Boparaiamrit\Tenancy\Commands\Cache;
use Boparaiamrit\Tenancy\Commands\Config\CacheCommand;
use Boparaiamrit\Tenancy\Commands\Config\ClearCommand;
use Boparaiamrit\Tenancy\Commands\Queue\ListenCommand;
use Boparaiamrit\Tenancy\Commands\Queue\RestartCommand;
use Boparaiamrit\Tenancy\Commands\Queue\WorkCommand;
use Boparaiamrit\Tenancy\Commands\Seeds\SeedCommand;
use Boparaiamrit\Tenancy\Commands\SetupCommand;
use Boparaiamrit\Tenancy\Commands\TinkerCommand;
use Illuminate\Support\ServiceProvider;

class TenancyServiceProvider extends ServiceProvider
{
	protected $defer = true;
	
	public function boot()
	{
		// Add Helper Function
		require_once __DIR__ . '/Helpers/helpers.php';
		
		/*
		 * Set configuration variables
		 */
		$this->mergeConfigFrom(__DIR__ . '/../../config/multitenant.php', 'multitenant');
		$this->publishes([
			__DIR__ . '/../../config/multitenant.php' => config_path('multitenant.php')
		]);
		
		$this->extendCommands();
	}
	
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		/** @noinspection PhpUndefinedMethodInspection */
		$this->app->bootstrapWith([Bootstrap\LoadConfiguration::class]);
		
		$this->app->bind(SetupCommand::class, function () {
			return new SetupCommand();
		});
	}
	
	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [
			SetupCommand::class,
		];
	}
	
	private function extendCommands()
	{
		/** @noinspection PhpUnusedParameterInspection */
		$this->app->extend('command.config.cache', function ($command, $app) {
			return new CacheCommand($app['files']);
		});
		
		/** @noinspection PhpUnusedParameterInspection */
		$this->app->extend('command.config.clear', function ($command, $app) {
			return new ClearCommand($app['files']);
		});
		
		/** @noinspection PhpUnusedParameterInspection */
		$this->app->extend('command.seed', function ($command, $app) {
			return new SeedCommand($app['db']);
		});
		
		/** @noinspection PhpUnusedParameterInspection */
		$this->app->extend('command.queue.work', function ($command, $app) {
			return new WorkCommand($app['queue.worker']);
		});
		
		/** @noinspection PhpUnusedParameterInspection */
		$this->app->extend('command.queue.listen', function ($command, $app) {
			return new ListenCommand($app['queue.listener']);
		});
		
		/** @noinspection PhpUnusedParameterInspection */
		$this->app->extend('command.queue.restart', function ($command, $app) {
			return new RestartCommand();
		});
		
		/** @noinspection PhpUnusedParameterInspection */
		$this->app->extend('command.cache.clear', function ($command, $app) {
			return new Cache\ClearCommand($app['cache']);
		});
		
		/** @noinspection PhpUnusedParameterInspection */
		$this->app->extend('command.tinker', function ($command, $app) {
			return new TinkerCommand();
		});
		
		// Register Commands
		$this->commands(SetupCommand::class);
	}
}
