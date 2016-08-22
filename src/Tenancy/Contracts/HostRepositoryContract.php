<?php

namespace Boparaiamrit\Tenancy\Contracts;


use Boparaiamrit\Framework\Contracts\BaseRepositoryContract;
use Boparaiamrit\Tenancy\Models\Host;

/**
 * Interface HostRepositoryContract
 *
 * @package Boparaiamrit\Tenancy\Contracts
 *
 * @property Host $Model
 */
interface HostRepositoryContract extends BaseRepositoryContract
{
	/**
	 * @param string $hostname
	 *
	 * @return Host
	 */
	public function findByHostname($hostname);
}
