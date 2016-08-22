<?php

namespace Boparaiamrit\Tenancy;


use Boparaiamrit\Tenancy\Commands\SetupCommand;
use Boparaiamrit\Tenancy\Contracts\CustomerRepositoryContract;
use Boparaiamrit\Tenancy\Contracts\HostRepositoryContract;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class TenancyEnvironment.
 *
 * Sets the tenant environment; overrules laravel core and sets the database connection
 */
class TenancyEnvironment
{
	/**
	 * @param Application $app
	 */
	public function setup($app)
	{
		// sets file access to as wide as possible, ignoring server masks
		umask(0);
		
		// bind tenancy environment into IOC
		$this->contractsBinding($app);
	}
	
	/**
	 * Binds all interfaces to the IOC container.
	 *
	 * @param Application $app
	 */
	protected function contractsBinding($app)
	{
		/*
		 * Tenant Customer repository
		 */
		$app->bind(Contracts\CustomerRepositoryContract::class, function () {
			return new Repositories\CustomerRepository(new Models\Customer());
		});
		
		/*
		 * Tenant Host repository
		 */
		$app->bind(Contracts\HostRepositoryContract::class, function () {
			return new Repositories\HostRepository(new Models\Host());
		});
		
		/*
		 * Tenant repository
		 */
		$app->bind(Contracts\CertificateRepositoryContract::class, function () {
			return new Repositories\CustomerRepository(new Models\Certificate());
		});
		
		/*
		 * Bind setup command into ioc
		 */
		$app->bind(SetupCommand::class, function ($app) {
			/** @var Application $app */
			return new SetupCommand(
				$app->make(CustomerRepositoryContract::class),
				$app->make(HostRepositoryContract::class)
			);
		});
	}
}
