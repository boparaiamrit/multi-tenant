<?php

namespace Boparaiamrit\Tenancy\Commands\Seeds;


use Boparaiamrit\Tenancy\Commands\TTenancyCommand;
use Illuminate\Contracts\Foundation\Application;
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
		$hostname = array_get($GLOBALS, 'hostname');
		
		if (empty($hostname)) {
			$Host     = $this->getHost();
			$hostname = $Host->identifier;
		}
		
		if ($hostname != config('env.default_host')) {
			/** @noinspection PhpUndefinedMethodInspection */
			$path = $this->laravel->bootstrapPath() . '/app.php';
			
			/** @noinspection PhpIncludeInspection */
			/** @var Application $app */
			$app = require $path;
			$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
		}
		
		$this->info(sprintf('Seeding starts for %s.', $hostname));
		parent::fire();
		$this->info(sprintf('Seeding ends for %s.', $hostname));
	}
}
