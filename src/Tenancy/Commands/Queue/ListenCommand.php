<?php

namespace Boparaiamrit\Tenancy\Commands\Queue;


use Boparaiamrit\Tenancy\Commands\TTenancyCommand;
use Illuminate\Queue\Listener;

class ListenCommand extends \Illuminate\Queue\Console\ListenCommand
{
	use TTenancyCommand;
	
	/**
	 * SeedCommand constructor.
	 *
	 * @param Listener $listener
	 *
	 */
	public function __construct(Listener $listener)
	{
		parent::__construct($listener);
	}
	
	/**
	 * Fires the command.
	 */
	public function fire()
	{
		parent::fire();
	}
}
