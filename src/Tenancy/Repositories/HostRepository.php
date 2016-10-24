<?php

namespace Boparaiamrit\Tenancy\Repositories;


use Boparaiamrit\Framework\Repositories\BaseRepository;
use Boparaiamrit\Tenancy\Contracts\HostRepositoryContract;
use Boparaiamrit\Tenancy\Models\Host;

class HostRepository extends BaseRepository implements HostRepositoryContract
{
	/**
	 * @param Host $hostname
	 *
	 * @return mixed
	 */
	public function findByHostname($hostname)
	{
		return $this->Model->where('hostname', $hostname)
						   ->orWhere('identifier', $hostname)
						   ->get();
	}
	
	/**
	 * Create a pagination object.
	 *
	 * @param int $perPage
	 *
	 * @return mixed
	 */
	public function paginated($perPage = 20)
	{
		return $this->Model->paginate($perPage);
	}
}
