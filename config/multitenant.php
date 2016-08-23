<?php

return [
	
	'database'   => 'system',
	
	/*
	 * Whether to use the middleware.
	 * If enabled the hostname will be globally set by the middleware, which ensures the correct
	 * tenant data is loaded to be used. Disabling auto detection comes down to you setting
	 * the current hostname or tenant by yourself.
	 */
	'middleware' => true
];
