<?php

namespace Boparaiamrit\Webserver\Generators\Webserver;


use Boparaiamrit\Webserver\Generators\FileGenerator;

class Nginx extends FileGenerator
{
	/**
	 * Generates the view that is written.
	 *
	 * @return \Illuminate\View\View
	 */
	public function generate()
	{
		$hostName       = $this->Host->hostname;
		$hostIdentifier = $this->Host->identifier;
		
		$machine = config('webserver.machine', 'ubuntu');
		
		if ($machine == 'ubuntu') {
			$listenSocket = 'unix:/var/run/php/php7.1-fpm.' . $hostIdentifier . '.sock';
		}
		
		return view('webserver::nginx.configuration', [
			'port'          => config('webserver.nginx.port.' . $machine),
			'host_name'     => $hostName,
			'group'         => config('webserver.group'),
			'config'        => config('webserver.nginx'),
			'log_path'      => config('webserver.log.path') . '/nginx-' . $hostIdentifier,
			'listen_socket' => $listenSocket
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
		
		return sprintf('%s%s.conf', config('webserver.nginx.path.' . $machine), $this->name());
	}
}

