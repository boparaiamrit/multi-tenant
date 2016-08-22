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
		
		$defaultUser = config('webserver.user');
		if ($defaultUser === true) {
			$config['user'] = $this->Host->identifier;
		} else if (is_string($defaultUser)) {
			$config['user'] = $defaultUser;
		} else if ($defaultUser === false) {
			$config['user'] = 'vagrant';
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
	 */
	public function register()
	{
		if (!$this->isInstalled()) {
			return;
		}
		
		// load the tenant include path
		$targetPath = array_get($this->configuration(), 'path');
		$confPath   = array_get($this->configuration(), 'main');
		
		$File = app('files');
		if (!str_contains($File->get($confPath), $targetPath)) {
			// save file to global include path
			$File->append($confPath, sprintf(array_get($this->configuration(), 'include'), $targetPath));
		}
		
		/*
		 * Register any depending services as well
		 */
		$depends = array_get($this->configuration(), 'depends', []);
		
		foreach ($depends as $depend) {
			$class = config("webserver.{$depend}.class");
			if (empty($class)) {
				continue;
			}
			/** @noinspection PhpUndefinedMethodInspection */
			(new $class($this->Host))->register();
		}
		
		// reload any services
		if (method_exists($this, 'serviceReload')) {
			$this->serviceReload();
		}
	}
}
