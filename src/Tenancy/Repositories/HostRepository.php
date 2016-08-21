<?php

namespace Boparaiamrit\Tenancy\Repositories;


use Boparaiamrit\Framework\Repositories\BaseRepository;
use Boparaiamrit\Tenancy\Contracts\HostRepositoryContract;
use Boparaiamrit\Tenancy\Models\Host;

class HostRepository extends BaseRepository implements HostRepositoryContract
{
	/**
	 * @var Host
	 */
	protected $Host;
	
	/**
	 * @param Host $hostname
	 *
	 * @return mixed
	 */
	public function findByHostname($hostname)
	{
		return $this->Host->where('hostname', $hostname)->first();
	}
	
	/**
	 * @return mixed
	 */
	public function getDefault()
	{
		$hostname = env('DEFAULT_HOST', 'my.promoto.dev');
		
		return $this->Host->where('hostname', $hostname)->first();
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
		return $this->Host->paginate($perPage);
	}
}
