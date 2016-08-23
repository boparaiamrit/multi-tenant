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
		$config = [
			'Host'      => $this->Host,
			'base_path' => base_path(),
			'group'     => config('webserver.group'),
			'config'    => config('webserver.fpm'),
		];
		
		$defaultUser = config('webserver.user');
		if ($defaultUser === true) {
			$config['user'] = $this->Host->identifier;
		} else if (is_string($defaultUser)) {
			$config['user'] = $defaultUser;
		} else if ($defaultUser === false) {
			$config['user'] = 'vagrant';
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
		return sprintf('%s%s.conf', config('webserver.fpm.path'), $this->name());
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
}
