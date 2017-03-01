<?php

return [
	
	'machine' => env('WEBSERVER_MACHINE', 'ubuntu'),
	
	'webservers' => ['nginx'],
	
	'user'  => env('WEBSERVER_USER'),
	
	/*
	 * The group the tenant files should belong to
	 */
	'group' => env('WEBSERVER_GROUP'),
	
	/*
	 * Logging specific settings
	 */
	'log'   => [
		// path where to store the webserver logs
		'path' => storage_path('logs'),
	],
	
	'php_path'   => [
		'ubuntu' => '/usr/bin/php'
	],
	
	/*
	 * Nginx
	 */
	'nginx'      => [
		'path'    => [
			'ubuntu' => '/etc/nginx/sites-enabled/'
		],
		'port'    => [
			'ubuntu' => 80
		],
		// path to service daemon, used to verify service exists
		'service' => [
			'ubuntu' => '/etc/init.d/nginx'
		]
	],
	
	/*
	 * PHP FPM
	 */
	'fpm'        => [
		'path'    => [
			'ubuntu' => '/etc/php/7.1/fpm/pool.d/'
		],
		/*
		 * base modifier for fpm pool port
		 * @example if base is 9000, will generate pool file for website Id 5 with port 9005
		 * @info this port is used in Nginx configurations for the PHP proxy
		 */
		'port'    => [
			'ubuntu' => 9000
		],
		// path to service daemon, used to verify service exists
		// path to service daemon, used to verify service exists
		'service' => [
			'ubuntu' => '/etc/init.d/php7.1-fpm'
		]
	],
	
	/*
     * Supervisor
     */
	'supervisor' => [
		'path'    => [
			'ubuntu' => '/etc/supervisor/conf.d/'
		],
		'service' => [
			'ubuntu' => '/etc/init.d/supervisor'
		]
	],
	
	/*
     * Env
     */
	'env'        => [
		'path' => base_path('envs/'),
	]
];
