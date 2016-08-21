<?php

namespace Boparaiamrit\Tenancy\Middleware;


use Boparaiamrit\Tenancy\TenancyServiceProvider;
use Closure;
use Boparaiamrit\Tenancy\Bootstrap\Configuration;
use Boparaiamrit\Tenancy\Models\Host;

class HostMiddleware
{
	public function handle($request, Closure $next)
	{
		/** @var Host $Host */
		$Host = app(TenancyServiceProvider::CUSTOMER_HOST);
		$redirect = $Host->redirectActionRequired();
		
		if ($Host && !empty($redirect)) {
			return $redirect;
		}
		
		(new Configuration($Host->identifier))->reload();
		
		return $next($request);
	}
}