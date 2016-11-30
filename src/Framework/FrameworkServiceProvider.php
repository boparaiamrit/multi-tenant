<?php

namespace Boparaiamrit\Framework;


use Illuminate\Support\ServiceProvider;

class FrameworkServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;
	
	public function boot()
	{
	}
	
	/**
	 * Register the service provider.
	 *
	 * @throws \Exception
	 */
	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/../../config/framework.php', 'framework');
		
		$packages = config('framework.packages');
		
		if (empty($packages)) {
			throw new \Exception("It seems config files are not available, boparaiamrit won't work without the configuration file");
		}
		
		foreach ($packages as $name => $package) {
			// register service provider for package
			if (class_exists(array_get($package, 'service-provider'))) {
				$this->app->register(array_get($package, 'service-provider'));
			}
			// set global state
			$this->app->bind("boparaiamrit.package.$name", function () use ($package) {
				return class_exists(array_get($package, 'service-provider')) ? $package : false;
			});
		}
	}
}
