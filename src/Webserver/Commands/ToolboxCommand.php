<?php

namespace Boparaiamrit\Webserver\Commands;


use Boparaiamrit\Framework\Commands\AbstractRootCommand;
use Boparaiamrit\Tenancy\Traits\DatabaseCommandTrait;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Symfony\Component\Console\Input\InputOption;

class ToolboxCommand extends AbstractRootCommand
{
	use DispatchesJobs, DatabaseCommandTrait;
	
	protected $signature = 'webserver:toolbox {--action=} {--customer=}';
	
	protected $description = 'Allows mutation of webserver related to tenancy.';
	
	/**
	 * Handles command execution.
	 */
	public function handle()
	{
		$Host = $this->getHostFromOption();
		
		$action = $this->option('action');
		if ($action == 'update' || $action == 'delete') {
			$this->dispatch(new WebserverCommand($Host->id, $action));
		} else {
			$this->error('Unknown action, please specify one.');
			
			return;
		}
	}
	
	/**
	 * @return array
	 */
	protected function getOptions()
	{
		return array_merge(
			[['action', null, InputOption::VALUE_REQUIRED, 'The action must be required.']],
			$this->getCustomerOption()
		);
	}
}
