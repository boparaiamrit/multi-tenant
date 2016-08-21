<?php

namespace Boparaiamrit\Tenancy\Repositories;


use Boparaiamrit\Framework\Repositories\BaseRepository;
use Boparaiamrit\Tenancy\Contracts\CertificateRepositoryContract;
use Boparaiamrit\Tenancy\Models\Certificate;
use Boparaiamrit\Tenancy\Models\Host;

class CertificateRepository extends BaseRepository implements CertificateRepositoryContract
{
	/**
	 * @param Host $Host
	 *
	 * @return Certificate
	 */
	public function findByHost(Host $Host)
	{
		return $Host->certificate;
	}
}
