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
		$hostname = array_get($GLOBALS, 'hostname');
		
		if (!empty($hostname)) {
			/** @noinspection PhpUndefinedMethodInspection */
			$path = $this->laravel->bootstrapPath() . '/app.php';
			
			/** @noinspection PhpIncludeInspection */
			/** @var Application $app */
			$app = require $path;
			$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
		}
		
		parent::fire();
	}
}
