<?php

namespace Boparaiamrit\Webserver\Generators\Webserver;


use Boparaiamrit\Webserver\Generators\AbstractFileGenerator;

class Supervisor extends AbstractFileGenerator
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
		
		
		return view('webserver::supervisor.configuration', $config);
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
			return;
		}
		
		$restart = array_get($this->configuration(), 'actions.restart');
		if (!empty($restart)) {
			exec($restart, $out, $test);
		}
		
		return $restart == 0;
	}
}
