<?php

namespace Hyn\Tenancy\Middleware;


use Closure;
use Hyn\Tenancy\Bootstrap\Configuration;
use Hyn\Tenancy\Models\Hostname;

class HostnameMiddleware
{
	public function handle($request, Closure $next)
	{
		/** @var Hostname $Hostname */
		$Hostname = app('tenant.hostname');
		$redirect = $Hostname->redirectActionRequired();
		
		if ($Hostname && !empty($redirect)) {
			return $redirect;
		}
		
		(new Configuration($Hostname->website->identifier))->reload();
		
		return $next($request);
	}
}