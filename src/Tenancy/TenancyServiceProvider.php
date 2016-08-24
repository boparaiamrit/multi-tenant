<?php

namespace Boparaiamrit\Tenancy;


use Boparaiamrit\Tenancy\Commands\Config\CacheCommand;
use Boparaiamrit\Tenancy\Commands\Config\ClearCommand;
use Boparaiamrit\Tenancy\Commands\Queue\WorkCommand;
use Boparaiamrit\Tenancy\Commands\Seeds\SeedCommand;
use Boparaiamrit\Tenancy\Commands\SetupCommand;
use Boparaiamrit\Tenancy\Contracts\CustomerRepositoryContract;
use Boparaiamrit\Tenancy\Contracts\HostRepositoryContract;
use Boparaiamrit\Tenancy\Helpers\RequestHelper;
use Boparaiamrit\Tenancy\Middleware\HostMiddleware;
use Boparaiamrit\Tenancy\Observers\CertificateObserver;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;


class TenancyServiceProvider extends ServiceProvider
{
	protected $defer = false;
	
	public function boot()
	{
		/** @var Application $app */
		$app = $this->app;
		/*
		 * Set configuration variables
		 */
		$this->mergeConfigFrom(__DIR__ . '/../../config/multitenant.php', 'multitenant');
		$this->publishes([__DIR__ . '/../../config/multitenant.php' => config_path('multitenant.php')], 'multitenant-config');
		
		
		// register middleware
		if (config('multitenant.middleware')) {
			$app->make(Kernel::class)
				->prependMiddleware(HostMiddleware::class);
		}
		
		// Register Observer
		Models\Host::observe(new Observers\HostObserver());
		Models\Customer::observe(new Observers\CustomerObserver());
		Models\Certificate::observe(new Observers\CertificateObserver());
		
		// Add Helper Function
		require_once __DIR__ . '/Helpers/helpers.php';
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
		
		$this->app->bind(Contracts\CustomerRepositoryContract::class, function () {
			return new Repositories\CustomerRepository(new Models\Customer());
		});
		
		$this->app->bind(Contracts\HostRepositoryContract::class, function () {
			return new Repositories\HostRepository(new Models\Host());
		});
		
		$this->app->bind(Contracts\CertificateRepositoryContract::class, function () {
			return new Repositories\CustomerRepository(new Models\Certificate());
		});
		
		$this->app->bind(SetupCommand::class, function ($app) {
			/** @var Application $app */
			return new SetupCommand(
				$app->make(CustomerRepositoryContract::class),
				$app->make(HostRepositoryContract::class)
			);
		});
		
		// Register Commands
		$this->commands(SetupCommand::class);
	}
	
	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [
			RequestHelper::CUSTOMER_HOST,
			CustomerRepositoryContract::class,
			HostRepositoryContract::class,
			SetupCommand::class,
		];
	}
}
