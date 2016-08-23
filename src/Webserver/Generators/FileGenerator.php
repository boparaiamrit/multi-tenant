<?php

namespace Boparaiamrit\Webserver\Generators;


use Boparaiamrit\Tenancy\Contracts\HostRepositoryContract;
use Boparaiamrit\Tenancy\Models\Host;
use Boparaiamrit\Webserver\Abstracts\AbstractGenerator;
use ReflectionClass;
use Symfony\Component\Console\Output\ConsoleOutput;

abstract class FileGenerator extends AbstractGenerator
{
	/**
	 * @var Host|HostRepositoryContract
	 */
	protected $Host;
	
	protected $Output;
	
	/**
	 * @param Host $Host
	 *
	 * @internal param ConsoleOutput $ConsoleOutput
	 */
	public function __construct(Host $Host)
	{
		$this->Host = $Host;
		
		$this->Output = new ConsoleOutput();
	}
	
	/**
	 * Writes the contents to disk on Creation.
	 *
	 * @return int
	 */
	public function onCreate()
	{
		if (is_null($this->Host)) {
			return false;
		}
		
		$serviceName = $this->beautifyName();
		
		$path = $this->publishPath();
		$data = $this->generate()->render();
		if (app('files')->put($path, $data, true)) {
			$this->output(sprintf('%s files has been published successfully.', $serviceName));
		}
		
		if ($this->serviceReload()) {
			$this->output(sprintf('%s has been restart successfully.', $serviceName));
		}
		
		return true;
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
		if ($this->Host->isDirty('identifier')) {
			$new = $this->Host->identifier;
			
			$this->Host->identifier = $this->Host->getOriginal('identifier');
			$this->onDelete();
			$this->Host->identifier = $new;
		}
		
		return $this->onCreate();
	}
	
	/**
	 * Action when deleting the Host.
	 *
	 * @return bool
	 */
	public function onDelete()
	{
		$serviceName = $this->beautifyName();
		
		if (app('files')->delete($this->publishPath())) {
			$this->output(sprintf('%s files has been deleted successfully.', $serviceName));
		}
		
		if ($this->serviceReload()) {
			$this->output(sprintf('%s has been restart successfully.', $serviceName));
		}
		
		return true;
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
			return false;
		}
		
		$test  = 1;
		$test2 = 1;
		
		$configtest = array_get($this->configuration(), 'actions.configtest');
		if (!empty($configtest)) {
			exec($configtest, $out, $test);
		}
		
		if ($test == 0) {
			$restart = array_get($this->configuration(), 'actions.restart');
			if (!empty($restart)) {
				exec($restart, $out, $test2);
			}
		} else {
			$test2 = 1;
		}
		
		return $test == 0 && $test2 == 0;
	}
	
	/**
	 * tests whether a certain service is installed.
	 *
	 * @return bool
	 */
	public function isInstalled()
	{
		$service = array_get($this->configuration(), 'service');
		
		return $service && app('files')->exists($service);
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
	 * @return string
	 */
	protected function beautifyName()
	{
		$name = $this->baseName();
		
		if ($name == 'fpm' || $name == 'ssl') {
			$name = mb_strtoupper($name);
		} else {
			$name = ucfirst($name);
		}
		
		return $name;
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
		return $this->Host->identifier;
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
			if (!empty($path) && app('files')->isDirectory($path)) {
				return $path;
			}
		}
		
		return '';
	}
	
	/**
	 * Registers the service.
	 *
	 * @return bool
	 */
	public function register()
	{
		if (!$this->isInstalled()) {
			return false;
		}
		
		// create a unique filename for the global include directory
		$path = $this->findPathForRegistration(array_get($this->configuration(), 'conf', []));
		
		$mask     = array_get($this->configuration(), 'mask', '%s');
		$filename = sprintf($mask, substr(md5(env('APP_KEY')), 0, 10));
		
		$webserviceFileLocation = sprintf('%s%s', $path, $filename);
		
		if (app('files')->exists($webserviceFileLocation)) {
			$depends = array_get($this->configuration(), 'depends', []);
			
			foreach ($depends as $depend) {
				$class = config("webserver.{$depend}.class");
				if (empty($class)) {
					continue;
				}
				/** @noinspection PhpUndefinedMethodInspection */
				(new $class($this->Host))->register();
			}
			
			return true;
		}
		
		// load the tenant include path
		$targetPath = array_get($this->configuration(), 'path');
		
		// save file to global include path
		app('files')->put($webserviceFileLocation, sprintf(array_get($this->configuration(), 'include'), $targetPath));
		
		// reload any services
		if (method_exists($this, 'serviceReload')) {
			if ($this->serviceReload()) {
				$this->output($this->baseName() . ' has been restart successfully.');
			}
		}
		
		return true;
	}
	
	public function output($message)
	{
		$this->Output->writeln(sprintf("<%s>$message</%s>", 'info', 'info'));
	}
}
