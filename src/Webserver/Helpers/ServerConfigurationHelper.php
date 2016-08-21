<?php

namespace Boparaiamrit\Webserver\Helpers;


use File;

/**
 * Class ServerConfigurationHelper.
 */
class ServerConfigurationHelper
{
	/**
	 * Creates directories if not yet existing
	 * Generated for any configured service in config.
	 */
	public function createDirectories()
	{
		foreach (config('webserver', []) as $key => $params) {
			$path = array_get($params, 'path');
			
			if ($path && !File::isDirectory($path)) {
				File::makeDirectory($path, 0755, true);
			}
		}
	}
}
