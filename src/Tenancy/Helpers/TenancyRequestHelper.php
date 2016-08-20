<?php

namespace Hyn\Tenancy\Helpers;


use App;
use Hyn\Tenancy\Contracts\HostnameRepositoryContract;
use Illuminate\Database\QueryException;

/**
 * Class TenancyRequestHelper.
 *
 * Helper class to identify requested hostname and website
 */
abstract class TenancyRequestHelper
{
	/**
	 * Loads Hostname models based on request.
	 *
	 * @param HostnameRepositoryContract $Hostname
	 *
	 * @return \Hyn\Tenancy\Models\Hostname
	 */
	public static function hostname(HostnameRepositoryContract $Hostname)
	{
		$HostnameInstance = null;
		
		try {
			if (!App::runningInConsole()) {
				$HostnameInstance = $Hostname->findByHostname(request()->getHost());
			}
			
			if (!$HostnameInstance) {
				$HostnameInstance = $Hostname->getDefault();
			}
		} catch (QueryException $e) {
			// table not found, set up not yet done
			if (preg_match('/\Qtable or view not found\E/', $e->getMessage())) {
				return;
			}
		}
		
		return $HostnameInstance;
	}
}
