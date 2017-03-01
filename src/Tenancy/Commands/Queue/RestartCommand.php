<?php

namespace Boparaiamrit\Tenancy\Commands\Queue;


use Boparaiamrit\Tenancy\Commands\TTenancyCommand;
use Boparaiamrit\Tenancy\Models\Host;

class RestartCommand extends \Illuminate\Queue\Console\RestartCommand
{
	use TTenancyCommand;
	
	/**
	 * Fires the command.
	 */
	public function fire()
	{
		$Hosts = $this->getHosts();
		foreach ($Hosts as $Host) {
			/** @noinspection PhpUndefinedMethodInspection */
			$this->laravel['cache']
				->forever('illuminate:queue:reload', time());
			
			/** @var Host $Host */
			$this->info(sprintf('Broadcasting queue reload signal for %s.', $Host->identifier));
		}
	}
}
