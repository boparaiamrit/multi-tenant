<?php

/**
 * All hyn-me/webserver related configuration options.
 *
 * @warning please be advised, read the documentation on http://hyn.me before editing
 *
 * None of the generated configurations will work as long as you don't add the paths to the corresponding webservice
 * configuration file. See documentation for more info.
 */
return [
	'webservers' => ['nginx'],
	
	'user'       => 'vagrant',
	
	/*
	 * The group the tenant files should belong to
	 */
	'group'      => 'vagrant',
	
	/*
	 * SSL
	 */
	'ssl'        => [
		'path' => storage_path('webserver/ssl'),
	],
	
	/*
	 * Logging specific settings
	 */
	'log'        => [
		// path where to store the webserver logs
		'path' => storage_path('logs'),
	],
	
	/*
	 * Nginx
	 */
	'nginx'      => [
		'path'    => storage_path('webserver/nginx/'),
		'class'   => 'Boparaiamrit\Webserver\Generators\Webserver\Nginx',
		'enabled' => true,
		'port'    => [
			'http'  => 80,
			'https' => 443,
		],
		// path to service daemon, used to verify service exists
		'service' => '/etc/init.d/nginx',
		// how to run actions for this service
		'actions' => [
			'configtest' => '/etc/init.d/nginx configtest',
			'restart'    => '/etc/init.d/nginx restart',
		],
		'conf'    => ['/etc/nginx/sites-enabled/'],
		'mask'    => '%s.conf',
		'include' => 'include %s*;',
		/*
		 * the nginx service depends on fpm
		 * during changes we will automatically trigger fpm as well
		 */
		'depends' => [
			'fpm',
			'supervisor'
		],
	],
	
	/*
	 * PHP FPM
	 */
	'fpm'        => [
		'path'    => storage_path('webserver/fpm/'),
		'class'   => 'Boparaiamrit\Webserver\Generators\Webserver\Fpm',
		'enabled' => true,
		'conf'    => ['/etc/php/7.0/fpm/pool.d/'],
		// path to service daemon, used to verify service exists
		'service' => '/etc/init.d/php7.0-fpm',
		// how to run actions for this service
		'actions' => [
			'configtest' => '/etc/init.d/php7.0-fpm -t',
			'restart'    => '/etc/init.d/php7.0-fpm restart',
		],
		'mask'    => '%s.conf',
		'include' => 'include=%s*;',
		/*
		 * base modifier for fpm pool port
		 * @example if base is 9000, will generate pool file for website Id 5 with port 9005
		 * @info this port is used in Nginx configurations for the PHP proxy
		 */
		'port'    => 9000,
	],
	
	/*
     * Supervisor
     */
	'supervisor' => [
		'path'    => storage_path('webserver/supervisor/'),
		'class'   => 'Boparaiamrit\Webserver\Generators\Webserver\Supervisor',
		'enabled' => true,
		'service' => '/etc/init.d/supervisor',
		// how to run actions for this service
		'actions' => [
			'restart' => '/etc/init.d/supervisor restart',
		]
	],
	
	/*
     * Env
     */
	'env'        => [
		'path'    => base_path('envs/'),
		'class'   => 'Boparaiamrit\Webserver\Generators\Webserver\Env',
		'enabled' => true,
	]
];
