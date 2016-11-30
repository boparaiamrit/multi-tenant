<?php

use Boparaiamrit\Tenancy\TenancyServiceProvider;
use Boparaiamrit\Webserver\WebserverServiceProvider;

return [
	'packages' => [
		'multitenant' => [
			'description'      => 'Multi tenancy for Laravel 5',
			'service-provider' => TenancyServiceProvider::class,
		],
		'webserver'   => [
			'description'      => 'Integration into and generation of configs for webservices',
			'service-provider' => WebserverServiceProvider::class,
		],
	],
];
