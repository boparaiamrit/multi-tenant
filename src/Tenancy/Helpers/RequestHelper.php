<?php

namespace Boparaiamrit\Tenancy\Helpers;


use Boparaiamrit\Tenancy\Contracts\HostRepositoryContract;
use Boparaiamrit\Tenancy\Models\Host;

/**
 * Class RequestHelper.
 *
 * Helper class to identify requested hostname and website
 */
abstract class RequestHelper
{
	const CUSTOMER_HOST = 'customer.host';
	
	/**
	 * Loads Hostname models based on request.
	 *
	 * @param HostRepositoryContract $Host
	 *
	 * @return Host
	 */
	public static function getHost(HostRepositoryContract $Host)
	{
		return $Host->findByHostname(request()->getHost());
	}
}
