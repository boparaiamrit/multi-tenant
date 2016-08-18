<?php

namespace Hyn\Tenancy\Traits;


use Hyn\Tenancy\Contracts\WebsiteRepositoryContract;
use Symfony\Component\Console\Input\InputOption;

trait TenantDatabaseCommandTrait
{
	/**
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	protected function getWebsitesFromOption()
	{
		$repository = app(WebsiteRepositoryContract::class);
		
		if ($this->option('tenant') == 'all') {
			return $repository->all();
		} else {
			return $repository
				->queryBuilder()
				->where('identifier', $this->option('tenant'))
				->get();
		}
	}
	
	/**
	 * @return array
	 */
	protected function getTenantOption()
	{
		return [['tenant', null, InputOption::VALUE_REQUIRED, 'The tenant(s) to apply on; use {all|identifier}']];
	}
}
