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
		$config = [
			'Host'      => $this->Host,
			'base_path' => base_path(),
		];
		
		$defaultUser = config('webserver.user');
		if ($defaultUser === true) {
			$config['user'] = $this->Host->identifier;
		} else if (is_string($defaultUser)) {
			$config['user'] = $defaultUser;
		}
		
		return view('webserver::supervisor.configuration', $config);
	}
	
	/**
	 * Provides the complete path to publish the generated content to.
	 *
	 * @return string
	 */
	protected function publishPath()
	{
		return sprintf('%s%s.conf', config('webserver.supervisor.path'), $this->name());
	}
	
	/**
	 * Reloads service if possible.
	 *
	 * @return bool
	 */
	protected function serviceReload()
	{
		if (!$this->isInstalled()) {
			return false;
		}
		
		$restart = array_get($this->configuration(), 'actions.restart');
		if (!empty($restart)) {
			exec($restart, $out, $test);
		}
		
		return $restart == 0;
	}
	
	/**
	 * Registers the service.
	 *
	 * @return bool|void
	 */
	public function register()
	{
		return true;
	}
}
