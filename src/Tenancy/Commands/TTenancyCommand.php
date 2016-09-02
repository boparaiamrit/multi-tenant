<?php

namespace Boparaiamrit\Tenancy\Commands;


use Boparaiamrit\Tenancy\Contracts\HostRepositoryContract;
use Boparaiamrit\Tenancy\Models\Host;
use Symfony\Component\Console\Input\InputOption;

trait TTenancyCommand
{
	/**
	 * @return Host
	 */
	protected function getHost()
	{
		$repository = app(HostRepositoryContract::class);
		
		$hostname = $this->option('hostname');
		
		if ($hostname == 'default') {
			$hostname = config('env.default_host');
		}
		
		$hostname = hostname_cleaner($hostname);
		
		/** @var Host $Host */
		$Host = $repository->findByHostname($hostname);
		
		if (is_null($Host)) {
			$this->error('Hostname not found');
			exit;
		}
		
		return $Host;
	}
	
	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		/** @noinspection PhpUndefinedMethodInspection */
		/** @noinspection PhpUndefinedClassInspection */
		return array_merge(parent::getOptions(), [
			['hostname', null, InputOption::VALUE_OPTIONAL, 'The hostname(s) to apply on; use {all|identifier}', 'default']
		]);
	}
}
