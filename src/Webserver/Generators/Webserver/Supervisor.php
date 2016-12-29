<?php

namespace Boparaiamrit\Webserver\Generators\Webserver;


use Boparaiamrit\Webserver\Generators\FileGenerator;

class Supervisor extends FileGenerator
{
	/**
	 * Generates the view that is written.
	 *
	 * @return \Illuminate\View\View
	 */
	public function generate()
	{
		$hostIdentifier = $this->Host->identifier;
		
		$user = config('webserver.user');
		if ($user === true) {
			$user = $hostIdentifier;
		}
		
		return view('webserver::supervisor.configuration', [
			'user'            => $user,
			'base_path'       => base_path(),
			'php_path'        => config('webserver.php_path.' . config('webserver.machine')),
			'host_identifier' => $hostIdentifier
		]);
	}
	
	/**
	 * Provides the complete path to publish the generated content to.
	 *
	 * @return string
	 */
	protected function publishPath()
	{
		$machine = config('webserver.machine');
		
		return sprintf('%s%s.conf', config('webserver.supervisor.path.' . $machine), $this->name());
	}
}
