<?php

return [
	
	'machine' => env('WEBSERVER_MACHINE', 'linux'),
	
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
		'mac'   => '/usr/local/bin/php',
		'linux' => '/usr/bin/php'
	],
	
	/*
	 * Nginx
	 */
	'nginx'      => [
		'path'    => storage_path('webserver/nginx/'),
		'port'    => [
			'mac'   => 8080,
			'linux' => 80
		],
		// path to service daemon, used to verify service exists
		'service' => [
			'mac'   => '/usr/local/bin/nginx',
			'linux' => '/etc/init.d/nginx'
		],
		// how to run actions for this service
		'actions' => [
			'configtest' => [
				'mac'   => '/usr/local/bin/nginx -t',
				'linux' => '/etc/init.d/nginx configtest'
			],
			'restart'    => [
				'mac'   => 'brew services restart nginx',
				'linux' => '/etc/init.d/nginx reload'
			]
		]
	],
	
	/*
	 * PHP FPM
	 */
	'fpm'        => [
		'path'    => storage_path('webserver/fpm/'),
		// path to service daemon, used to verify service exists
		// path to service daemon, used to verify service exists
		'service' => [
			'mac'   => '/usr/local/sbin/php70-fpm',
			'linux' => '/etc/init.d/php7.0-fpm'
		],
		// how to run actions for this service
		'actions' => [
			'configtest' => [
				'mac'   => '/usr/local/sbin/php70-fpm configtest',
				'linux' => '/etc/init.d/php7.0-fpm status'
			],
			'restart'    => [
				'mac'   => 'brew services restart php70',
				'linux' => '/etc/init.d/php7.0-fpm restart'
			]
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
		'service' => [
			'mac'   => '/usr/local/bin/supervisord',
			'linux' => '/etc/init.d/supervisor'
		],
		// how to run actions for this service
		'actions' => [
			'configtest' => [
				'mac'   => '',
				'linux' => ''
			],
			'restart'    => [
				'mac'   => 'brew services restart supervisor',
				'linux' => '/etc/init.d/supervisor restart'
			]
		],
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
	]
];
