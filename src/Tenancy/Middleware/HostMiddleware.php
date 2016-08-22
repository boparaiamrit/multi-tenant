<?php

namespace Boparaiamrit\Tenancy\Middleware;


use Boparaiamrit\Tenancy\Bootstrap\Configuration;
use Boparaiamrit\Tenancy\Contracts\HostRepositoryContract;
use Boparaiamrit\Tenancy\Helpers\RequestHelper;
use Closure;
use Illuminate\Contracts\Foundation\Application;

class HostMiddleware
{
	public function handle($request, Closure $next)
	{
		$Host = $this->setUpHost();
		
		$redirectTo = $Host->redirectActionRequired();
		
		if ($Host && !empty($redirectTo)) {
			return $redirectTo;
		}
		
		(new Configuration($Host->identifier))->reload();
		
		return $next($request);
	}
	
	private function setUpHost()
	{
		app('app')->singleton(RequestHelper::CUSTOMER_HOST, function ($app) {
			/** @var Application $app */
			return RequestHelper::getHost($app->make(HostRepositoryContract::class));
		});
		
		return app(RequestHelper::CUSTOMER_HOST);
	}
}