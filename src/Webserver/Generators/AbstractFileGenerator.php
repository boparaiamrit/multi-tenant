<?php

namespace Hyn\Webserver\Generators;


use File;
use Hyn\Tenancy\Models\Website;
use Hyn\Webserver\Abstracts\AbstractGenerator;
use ReflectionClass;

abstract class AbstractFileGenerator extends AbstractGenerator
{
	/**
	 * @var Website
	 */
	protected $Website;
	
	/**
	 * @param Website $Website
	 */
	public function __construct(Website $Website)
	{
		$this->Website = $Website;
	}
	
	/**
	 * Writes the contents to disk on Update.
	 *
	 * @uses onCreate
	 *
	 * @return int
	 */
	public function onUpdate()
	{
		if ($this->Website->isDirty('identifier')) {
			$new = $this->Website->identifier;
			
			$this->Website->identifier = $this->Website->getOriginal('identifier');
			$this->onDelete();
			$this->Website->identifier = $new;
		}
		
		return $this->onCreate();
	}
	
	/**
	 * Action when deleting the Website.
	 *
	 * @return bool
	 */
	public function onDelete()
	{
		return File::delete($this->publishPath()) && $this->serviceReload();
	}
	
	/**
	 * Provides the complete path to publish the generated content to.
	 *
	 * @return string
	 */
	abstract protected function publishPath();
	
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
		
		$configtest = array_get($this->configuration(), 'actions.configtest');
		if (!empty($configtest)) {
			exec($configtest, $out, $test);
		}
		
		if ($test == 0) {
			$restart = array_get($this->configuration(), 'actions.restart');
			if (!empty($restart)) {
				exec($restart, $out, $test);
			}
		} else {
			$restart = 1;
		}
		
		return $test == 0 && $restart == 0;
	}
	
	/**
	 * tests whether a certain service is installed.
	 *
	 * @return bool
	 */
	public function isInstalled()
	{
		$service = array_get($this->configuration(), 'service');
		
		return $service && File::exists($service);
	}
	
	/**
	 * Loads possible configuration from config file.
	 *
	 * @throws \Exception
	 *
	 * @return array
	 */
	public function configuration()
	{
		$configuration = config('webserver');
		if (!$configuration || !array_has($configuration, $this->baseName())) {
			throw new \Exception("No configuration for {$this->baseName()}");
		}
		
		return array_get($configuration, $this->baseName());
	}
	
	/**
	 * @return string
	 */
	protected function baseName()
	{
		$reflect = new ReflectionClass($this);
		
		return strtolower($reflect->getShortName());
	}
	
	/**
	 * Writes the contents to disk on Creation.
	 *
	 * @return int
	 */
	public function onCreate()
	{
		// take no action with no hostnames
		if ($this->Website->hostnames->count() == 0) {
			return;
		}
		
		return File::put(
			$this->publishPath(),
			$this->generate()->render(),
			true
		) && $this->serviceReload();
	}
	
	/**
	 * Generates the content.
	 *
	 * @return \Illuminate\View\View
	 */
	abstract public function generate();
	
	/**
	 * @param string $from
	 * @param string $to
	 *
	 * @return void
	 */
	public function onRename($from, $to)
	{
		// .. no implementation
	}
	
	/**
	 * The filename.
	 *
	 * @return string
	 */
	public function name()
	{
		return $this->Website->identifier;
	}
	
	/**
	 * Registers the service.
	 */
	public function register()
	{
		if (!$this->isInstalled()) {
			return;
		}
		
		// create a unique filename for the global include directory
		$webserviceFileLocation = sprintf('%s%s',
			$this->findPathForRegistration(array_get($this->configuration(), 'conf', [])),
			sprintf(array_get($this->configuration(), 'mask', '%s'), substr(md5(env('APP_KEY')), 0, 10))
		);
		
		// load the tenant include path
		$targetPath = array_get($this->configuration(), 'path');
		
		// save file to global include path
		File::put($webserviceFileLocation, sprintf(array_get($this->configuration(), 'include'), $targetPath));
		
		/*
		 * Register any depending services as well
		 */
		$depends = array_get($this->configuration(), 'depends', []);
		
		foreach ($depends as $depend) {
			$class = config("webserver.{$depend}.class");
			if (empty($class)) {
				continue;
			}
			(new $class($this->Website))->register();
		}
		
		// reload any services
		if (method_exists($this, 'serviceReload')) {
			$this->serviceReload();
		}
	}
	
	/**
	 * Finds first directory that exists.
	 *
	 * @param array $paths
	 *
	 * @return string
	 */
	protected function findPathForRegistration($paths = [])
	{
		foreach ($paths as $path) {
			if (!empty($path) && File::isDirectory($path)) {
				return $path;
			}
		}
	}
}
