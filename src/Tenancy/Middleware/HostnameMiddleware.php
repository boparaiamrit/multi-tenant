<?php

namespace Hyn\Tenancy\Middleware;

use Closure;

class HostnameMiddleware
{
    public function handle($request, Closure $next)
    {
        /** @var \Hyn\Tenancy\Models\Hostname $Hostname */
        $Hostname = app('tenant.hostname');
        if ($Hostname && ! is_null($redirect = $Hostname->redirectActionRequired())) {
            return $redirect;
        }

        return $next($request);
    }
}
