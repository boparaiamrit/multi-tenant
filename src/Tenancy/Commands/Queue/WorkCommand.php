<?php

namespace Boparaiamrit\Tenancy\Commands\Queue;


use Boparaiamrit\Tenancy\Commands\TTenancyCommand;
use Illuminate\Queue\Worker;

class WorkCommand extends \Illuminate\Queue\Console\WorkCommand
{
	use TTenancyCommand;
	
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
		$this->checkForHost();
		
		parent::fire();
	}
}
