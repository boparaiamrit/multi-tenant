<?php

namespace Boparaiamrit\Tenancy\Bootstrap;


use Dotenv\Dotenv;
use Illuminate\Config\Repository;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class Configuration
{
	protected $app;
	protected $host;
	
	function __construct($host)
	{
		$this->app  = app('app');
		$this->host = $host;
	}
	
	public function reload()
	{
		$this->reloadEnv();
		$this->reloadConfig();
	}
	
	private function reloadEnv()
	{
		$environmentPath = base_path() . '/envs';
		$environmentFile = '.' . $this->host . '.env';
		
		(new Dotenv($environmentPath, $environmentFile))->overload();
	}
	
	private function reloadConfig()
	{
		$items = [];
		// First we will see if we have a cache configuration file. If we do, we'll load
		// the configuration items from that file so that it is very quick. Otherwise
		// we will need to spin through every configuration file and load them all.
		if (file_exists($cached = $this->getCachedConfigPath())) {
			/** @noinspection PhpIncludeInspection */
			$items = require $cached;
			
			$loadedFromCache = true;
		}
		
		// Next we will spin through all of the configuration files in the configuration
		// directory and load each one into the repository. This will make all of the
		// options available to the developer for use in various parts of this app.
		if (!isset($loadedFromCache)) {
			$items = $this->loadConfigurationFiles();
		}
		
		$this->app->extend('config', function () use ($items) {
			return new Repository($items);
		});
	}
	
	private function getCachedConfigPath()
	{
		return $this->app->bootstrapPath() . '/cache/' . $this->host . '/config.php';
	}
	
	/**
	 * Load the configuration items from all of the files.
	 *
	 */
	protected function loadConfigurationFiles()
	{
		$items = [];
		foreach ($this->getConfigurationFiles() as $key => $path) {
			/** @noinspection PhpIncludeInspection */
			array_set($items, $key, require $path);
		}
		
		return $items;
	}
	
	/**
	 * Get all of the configuration files for the application.
	 *
	 * @return array
	 */
	protected function getConfigurationFiles()
	{
		$files = [];
		
		$configPath = realpath($this->app->configPath());
		
		foreach (Finder::create()->files()->name('*.php')->in($configPath) as $file) {
			$nesting = $this->getConfigurationNesting($file, $configPath);
			
			$files[ $nesting . basename($file->getRealPath(), '.php') ] = $file->getRealPath();
		}
		
		return $files;
	}
	
	/**
	 * Get the configuration file nesting path.
	 *
	 * @param SplFileInfo|\Symfony\Component\Finder\SplFileInfo $file
	 * @param  string                                           $configPath
	 *
	 * @return string
	 */
	protected function getConfigurationNesting(SplFileInfo $file, $configPath)
	{
		$directory = dirname($file->getRealPath());
		
		if ($tree = trim(str_replace($configPath, '', $directory), DIRECTORY_SEPARATOR)) {
			$tree = str_replace(DIRECTORY_SEPARATOR, '.', $tree) . '.';
		}
		
		return $tree;
	}
}
