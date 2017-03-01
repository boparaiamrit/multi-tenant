<?php

namespace Boparaiamrit\Tenancy\Commands\Seeds;


use Boparaiamrit\Tenancy\Commands\TTenancyCommand;
use Boparaiamrit\Tenancy\Models\Host;
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
		$Hosts = $this->getHosts();
		
		foreach ($Hosts as $Host) {
			/** @var Host $Host */
			$hostname = $Host->identifier;

			array_set($GLOBALS, 'hostname', $hostname);

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
}
