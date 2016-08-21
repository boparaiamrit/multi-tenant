<?php

namespace Boparaiamrit\Tenancy\Contracts;


use Boparaiamrit\Framework\Contracts\BaseRepositoryContract;
use Boparaiamrit\Tenancy\Models\Host;

interface HostRepositoryContract extends BaseRepositoryContract
{
	/**
	 * @param string $hostname
	 *
	 * @return Host
	 */
	public function findByHostname($hostname);
	
	/**
	 * @return Host
	 */
	public function getDefault();
}
