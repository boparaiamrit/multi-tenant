<?php

namespace Boparaiamrit\Tenancy\Contracts;


use Boparaiamrit\Framework\Contracts\BaseRepositoryContract;
use Boparaiamrit\Tenancy\Models\Host;
use Illuminate\Database\Eloquent\Collection;

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
	 * @return Host|Collection
	 */
	public function findByHostname($hostname);
}
