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
	
	'user'       => env('WEBSERVER_USER'),
	
	/*
	 * The group the tenant files should belong to
	 */
	'group'      => env('WEBSERVER_GROUP'),
	
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
		]
	],
	
	/*
	 * PHP FPM
	 */
	'fpm'        => [
		'path'    => storage_path('webserver/fpm/'),
		// path to service daemon, used to verify service exists
		'service' => '/etc/init.d/php7.0-fpm',
		// how to run actions for this service
		'actions' => [
			'configtest' => '/etc/init.d/php7.0-fpm -t',
			'restart'    => '/etc/init.d/php7.0-fpm restart',
		],
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
		'path' => base_path('envs/'),
	],
	
	/*
	 * SSL
	 */
	'ssl'        => [
		'path' => storage_path('webserver/ssl'),
	],
];
