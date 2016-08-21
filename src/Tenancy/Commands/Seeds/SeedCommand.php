<?php

namespace Boparaiamrit\Tenancy\Commands\Seeds;


use Boparaiamrit\Tenancy\Contracts\HostRepositoryContract;
use Boparaiamrit\Tenancy\Models\Host;
use Boparaiamrit\Tenancy\Traits\DatabaseCommandTrait;
use Illuminate\Database\ConnectionResolverInterface as Resolver;

class SeedCommand extends \Illuminate\Database\Console\Seeds\SeedCommand
{
	use DatabaseCommandTrait;
	/**
	 * @var HostRepositoryContract|Host
	 */
	protected $Host;
	
	/**
	 * SeedCommand constructor.
	 *
	 * @param Resolver $resolver
	 */
	public function __construct(Resolver $resolver)
	{
		parent::__construct($resolver);
		
		$this->Host = app(HostRepositoryContract::class);
	}
	
	/**
	 * Fires the command.
	 */
	public function fire()
	{
		// if no customer option is set, simply run the native laravel seeder
		if (!$this->option('customer')) {
			$this->error('No Customer Provided.');
			die;
		}
		
		$Hosts = $this->getHostsFromOption();
		
		// forces database to customer
		if (!$this->option('database')) {
			$this->input->setOption('database', config('multitenant.database'));
		}
		
		foreach ($Hosts as $Host) {
			/** @var Host $Host */
			$this->info("Seeding for {$Host->identifier}");
			
			app('config')->set('database.connections.main.database', $Host->identifier);
			
			$this->resolver->setDefaultConnection(config('database.default'));
			
			$this->getSeeder()->run();
		}
	}
	
	/**
	 * @return array
	 */
	protected function getOptions()
	{
		return array_merge(
			parent::getOptions(),
			$this->getCustomerOption()
		);
	}
}
