<?php

namespace Boparaiamrit\Tenancy\Commands;


use Boparaiamrit\Tenancy\Bootstrap\LoadConfiguration;
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
		
		/** @var Host $Host */
		$Host = $repository->queryBuilder()
						   ->where('identifier', $this->option('host'))
						   ->first();
		if (is_null($Host)) {
			$this->error('Host not found');
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
		return array_merge(parent::getOptions(), [['host', null, InputOption::VALUE_OPTIONAL, 'The host(s) to apply on; use {all|identifier}', 'default']]);
	}
}
