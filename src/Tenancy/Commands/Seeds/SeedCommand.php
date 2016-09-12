<?php

namespace Boparaiamrit\Tenancy\Commands\Seeds;


use Boparaiamrit\Tenancy\Commands\TTenancyCommand;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\ConnectionResolverInterface as Resolver;
use Illuminate\Cache\Repository as Cache;

class SeedCommand extends \Illuminate\Database\Console\Seeds\SeedCommand
{
	use TTenancyCommand;
	
	/**
	 * SeedCommand constructor.
	 *
	 * @param Resolver $resolver
	 */
	public function __construct(Resolver $resolver)
	{
		parent::__construct($resolver);
	}
	
	/**
	 * Fires the command.
	 */
	public function fire()
	{
		parent::fire();
	}
}
