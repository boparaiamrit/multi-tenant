<?php

namespace Boparaiamrit\Webserver\Commands;


use Boparaiamrit\Tenancy\Commands\TTenancyCommand;
use Boparaiamrit\Webserver\Generators\Webserver\Env;
use Boparaiamrit\Webserver\Generators\Webserver\Fpm;
use Boparaiamrit\Webserver\Generators\Webserver\Nginx;
use Boparaiamrit\Webserver\Generators\Webserver\Supervisor;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class WebserverCommand extends Command
{
	use TTenancyCommand;
	
	protected $name = 'webserver';
	
	/**
	 * Handles command execution.
	 */
	public function fire()
	{
		$Hosts = $this->getHosts();
		
		foreach ($Hosts as $Host) {
			$action = $this->argument('action');
			
			if (!in_array($action, ['update', 'delete'])) {
				return;
			}
			
			$action = sprintf('on%s', ucfirst($action));
			
			// Php FPM
			(new Fpm($Host))->{$action}();
			// Supervisor
			(new Supervisor($Host))->{$action}();
			// Webservers
			(new Nginx($Host))->{$action}();
			// Env
			(new Env($Host))->{$action}();
		}
	}
	
	public function getArguments()
	{
		return array_merge(parent::getArguments(), [
			['action', null, InputArgument::REQUIRED, 'Action Required']
		]);
	}
	
}
