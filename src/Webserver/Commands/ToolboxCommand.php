<?php

namespace Boparaiamrit\Webserver\Commands;


use Boparaiamrit\Framework\Commands\AbstractCommand;
use Boparaiamrit\Tenancy\Commands\TTenancyCommand;
use Symfony\Component\Console\Input\InputArgument;

class ToolboxCommand extends AbstractCommand
{
	use TTenancyCommand;
	
	protected $name = 'webserver:toolbox';
	
	/**
	 * Handles command execution.
	 */
	public function fire()
	{
		$Hosts = $this->getHosts();
		
		foreach ($Hosts as $Host) {
			$action = $this->argument('action');
			if ($action == 'update' || $action == 'delete') {
				(new WebserverCommand($Host, $action))->fire();
			} else {
				$this->error('Unknown action, please specify one.');
				
				return;
			}
		}
	}
	
	public function getArguments()
	{
		return array_merge(parent::getArguments(), [
			['action', null, InputArgument::REQUIRED, 'Action Required']
		]);
	}
	
}
