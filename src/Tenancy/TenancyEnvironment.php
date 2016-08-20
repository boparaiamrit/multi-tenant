<?php

namespace Hyn\Tenancy;


use Hyn\Tenancy\Helpers\TenancyRequestHelper;
use Illuminate\Foundation\Application;

/**
 * Class TenancyEnvironment.
 *
 * Sets the tenant environment; overrules laravel core and sets the database connection
 */
class TenancyEnvironment
{
	/**
	 * @var \Illuminate\Contracts\Foundation\Application
	 */
	protected $app;
	
	/**
	 * @var \Hyn\Tenancy\Models\Hostname
	 */
	protected $hostname;
	
	/**
	 * @var \Hyn\Tenancy\Models\Website
	 */
	protected $website;
	
	public function __construct($app)
	{
		// share the application
		$this->app = $app;
		
		// sets file access to as wide as possible, ignoring server masks
		umask(0);
		
		// bind tenancy environment into IOC
		$this->setupBinds();
		
		// load hostname object or default
		$this->hostname = TenancyRequestHelper::hostname(
			$this->app->make(Contracts\HostnameRepositoryContract::class)
		);
		
		// set website
		$this->website = !is_null($this->hostname) ? $this->hostname->website : null;
	}
	
	/**
	 * Binds all interfaces to the IOC container.
	 */
	protected function setupBinds()
	{
		/*
		 * Tenant repository
		 */
		$this->app->bind(Contracts\CustomerRepositoryContract::class, function () {
			return new Repositories\CustomerRepository(new Models\Customer());
		});
		/*
		 * Tenant hostname repository
		 */
		$this->app->bind(Contracts\HostnameRepositoryContract::class, function () {
			return new Repositories\HostnameRepository(new Models\Hostname());
		});
		/*
		 * Tenant website repository
		 */
		$this->app->bind(Contracts\WebsiteRepositoryContract::class, function ($app) {
			/** @var Application $app */
			return new Repositories\WebsiteRepository(
				new Models\Website(),
				$app->make(Contracts\HostnameRepositoryContract::class)
			);
		});
		
		/*
         * Tenant hostname
         */
		$this->app->singleton('tenant.hostname', function () {
			return $this->hostname;
		});
	}
}
