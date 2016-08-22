<?php

namespace Boparaiamrit\Tenancy\Commands\Queue;


use Boparaiamrit\Tenancy\Traits\DatabaseCommandTrait;
use Illuminate\Queue\Worker;

class WorkCommand extends \Illuminate\Queue\Console\WorkCommand
{
	use DatabaseCommandTrait;
	
	/**
	 * SeedCommand constructor.
	 *
	 * @param Worker $worker
	 *
	 */
	public function __construct(Worker $worker)
	{
		parent::__construct($worker);
	}
	
	/**
	 * Fires the command.
	 */
	public function fire()
	{
		// if no tenant option is set, simply run the native laravel seeder
		if (!$this->option('customer')) {
			parent::fire();
		} else {
			$Host = $this->getHostFromOption();
			
			app('config')->set('database.connections.main.database', $Host->identifier);
			
			parent::fire();
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
