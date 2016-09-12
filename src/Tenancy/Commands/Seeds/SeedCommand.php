<?php

namespace Boparaiamrit\Tenancy\Commands\Seeds;


use Boparaiamrit\Tenancy\Commands\TTenancyCommand;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\ConnectionResolverInterface as Resolver;

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
		$this->reboot();
		
		parent::fire();
	}
	
	private function reboot()
	{
		/** @noinspection PhpUndefinedMethodInspection */
		$path = $this->laravel->bootstrapPath() . '/app.php';
		
		/** @noinspection PhpIncludeInspection */
		$app = require $path;
		
		$app->make(Kernel::class)->bootstrap();
	}
}
