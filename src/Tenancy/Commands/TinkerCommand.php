<?php

namespace Boparaiamrit\Tenancy\Commands;


class TinkerCommand extends \Illuminate\Foundation\Console\TinkerCommand
{
	use TTenancyCommand;
	
	/**
	 * Fires the command.
	 */
	public function handle()
	{
		$Hosts = $this->getHosts();
		
		$Host = $Hosts->shift();
		
		if (!empty($Host)) {
			array_set($GLOBALS, 'hostname', $Host->identifier);
			
			/** @noinspection PhpUndefinedMethodInspection */
			$path = $this->laravel->bootstrapPath() . '/app.php';
			
			/** @noinspection PhpIncludeInspection */
			$app = require $path;
			$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
			
			$this->fire();
		} else {
			$this->error('No host provided.');
		}
	}
}
