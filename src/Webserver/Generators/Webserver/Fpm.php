<?php

namespace Boparaiamrit\Webserver\Generators\Webserver;


use Boparaiamrit\Webserver\Generators\FileGenerator;

class Fpm extends FileGenerator
{
	/**
	 * Generates the view that is written.
	 *
	 * @return \Illuminate\View\View
	 */
	public function generate()
	{
		$hostIdentifier = $this->Host->identifier;
		
		$machine = config('webserver.machine');
		
		if ($machine == 'ubuntu') {
			$listenSocket = '/var/run/php/php7.0-fpm.' . $hostIdentifier . '.sock';
		}
		
		$config = [
			'machine'         => $machine,
			'group'           => config('webserver.group'),
			'base_path'       => base_path(),
			'listen_socket'   => $listenSocket,
			'host_identifier' => $hostIdentifier
		];
		
		$defaultUser = config('webserver.user');
		if ($defaultUser === true) {
			$config['user'] = $hostIdentifier;
		} else if (is_string($defaultUser)) {
			$config['user'] = $defaultUser;
		}
		
		return view('webserver::fpm.configuration', $config);
	}
	
	/**
	 * Provides the complete path to publish the generated content to.
	 *
	 * @return string
	 */
	protected function publishPath()
	{
		$machine = config('webserver.machine');
		
		return sprintf('%s%s.conf', config('webserver.fpm.path.' . $machine), $this->name());
	}
}
