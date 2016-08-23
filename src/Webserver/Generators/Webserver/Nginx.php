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
		return view('webserver::nginx.configuration', [
			'Host'        => $this->Host,
			'public_path' => public_path(),
			'log_path'    => config('webserver.log.path') . "/nginx-{$this->Host->identifier}",
			'config'      => config('webserver.nginx'),
			'fpm_port'    => config('webserver.fpm.port'),
		]);
	}
	
	/**
	 * Provides the complete path to publish the generated content to.
	 *
	 * @return string
	 */
	protected function publishPath()
	{
		return sprintf('%s%s.conf', config('webserver.nginx.path'), $this->name());
	}
}
