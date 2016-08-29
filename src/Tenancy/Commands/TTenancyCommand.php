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
		
		$identifier = $this->option('host');
		
		if ($identifier == 'default') {
			$identifier = config('env.default_host');
		}
		
		/** @var Host $Host */
		$Host = $repository->queryBuilder()
						   ->where('identifier', $identifier)
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
