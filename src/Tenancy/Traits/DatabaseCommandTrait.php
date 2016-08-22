<?php

namespace Boparaiamrit\Tenancy\Traits;


use Boparaiamrit\Tenancy\Contracts\HostRepositoryContract;
use Boparaiamrit\Tenancy\Models\Host;
use Symfony\Component\Console\Input\InputOption;

trait DatabaseCommandTrait
{
	/**
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	protected function getHostsFromOption()
	{
		$repository = app(HostRepositoryContract::class);
		
		if ($this->option('customer') == 'all') {
			return $repository->all();
		} else {
			return $repository
				->queryBuilder()
				->where('identifier', $this->option('customer'))
				->get();
		}
	}
	
	/**
	 * @return Host
	 */
	protected function getHostFromOption()
	{
		$repository = app(HostRepositoryContract::class);
		
		/** @var Host $Host */
		$Host = $repository->queryBuilder()
						   ->where('identifier', $this->option('customer'))
						   ->first();
		
		return $Host;
	}
	
	/**
	 * @return array
	 */
	protected function getCustomerOption()
	{
		return [['customer', null, InputOption::VALUE_REQUIRED, 'The customer(s) to apply on; use {all|identifier}']];
	}
}
