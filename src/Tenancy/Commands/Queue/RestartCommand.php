<?php

namespace Boparaiamrit\Tenancy\Commands\Queue;


use Boparaiamrit\Tenancy\Commands\TTenancyCommand;

class RestartCommand extends \Illuminate\Queue\Console\RestartCommand
{
	use TTenancyCommand;
	
	/**
	 * Fires the command.
	 */
	public function fire()
	{
		/** @noinspection PhpUndefinedMethodInspection */
		$this->laravel['cache']
			->forever('illuminate:queue:restart', time());
		
		$this->info('Broadcasting queue restart signal.');
	}
}
