<?php

return [
	
	'database'   => 'system',
	
	/*
	 * Whether to use the middleware.
	 * If enabled the hostname will be globally set by the middleware, which ensures the correct
	 * tenant data is loaded to be used. Disabling auto detection comes down to you setting
	 * the current hostname or tenant by yourself.
	 */
	'middleware' => true,
	
	/*
	 * The queue to run webserver tasks on
	 * The specified queue name must have root privileges. If no value specified the default queue is
	 * used.
	 */
	'queue'      => [
		'root'  => null,
		'other' => null,
	]
];
