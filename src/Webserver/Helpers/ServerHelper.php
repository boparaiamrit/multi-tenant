<?php

namespace Boparaiamrit\Webserver\Helpers;


/**
 * Class ServerConfigurationHelper.
 */
class ServerHelper
{
	/**
	 * Creates directories if not yet existing
	 * Generated for any configured service in config.
	 */
	public static function createDirectories()
	{
		foreach (config('webserver', []) as $key => $params) {
			$path = array_get($params, 'path');
			$File = app('files');
			if ($path && !$File->isDirectory($path)) {
				$File->makeDirectory($path, 0755, true);
			}
		}
	}
}
