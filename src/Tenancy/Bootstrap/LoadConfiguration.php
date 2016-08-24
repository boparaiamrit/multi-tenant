<?php

namespace Boparaiamrit\Tenancy\Bootstrap;


use Dotenv\Dotenv;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class LoadConfiguration
{
	/**
	 * @var Application
	 */
	protected $app;
	
	/**
	 * @var string
	 */
	protected $host;
	
	/**
	 * @param Application $app
	 */
	public function bootstrap(Application $app)
	{
		$this->app = $app;
		
		if (!$this->envReloaded()) {
			return;
		}
		
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
		
		$app->extend('config', function () use ($items) {
			return new Repository($items);
		});
		
		/** @var Repository $Config */
		$Config = $this->app['config'];
		/** @noinspection PhpUndefinedMethodInspection */
		$app->detectEnvironment(function () use ($Config) {
			return $Config->get('app.env', 'production');
		});
		
		date_default_timezone_set($Config->get('app.timezone'));
		
		mb_internal_encoding('UTF-8');
	}
	
	private function envReloaded()
	{
		global $argv;
		if (!empty($argv)) {
			$commandComponents = $argv;
			array_shift($commandComponents);
			
			$host = null;
			foreach ($commandComponents as $commandComponent) {
				if (str_contains($commandComponent, '--host=')) {
					$host = explode('=', $commandComponent);
					if (count($host) == 2 && array_has($host, '1')) {
						$host = array_get($host, '1');
					}
				}
			}
		} else {
			$host = request()->getHost();
			$host = str_replace(['.'], '', $host);
		}
		
		if (empty($host)) {
			return false;
		}
		
		$envPath = base_path() . '/envs';
		$envFile = '.' . $host . '.env';
		
		$filePath = $envPath . DIRECTORY_SEPARATOR . $envFile;
		if (file_exists($filePath)) {
			(new Dotenv($envPath, $envFile))->overload();
			$this->host = $host;
			
			return true;
		}
		
		return false;
	}
	
	private function getCachedConfigPath()
	{
		/** @noinspection PhpUndefinedMethodInspection */
		return $this->app->bootstrapPath() . '/cache/' . $this->host . '/config.php';
	}
	
	/**
	 * Load the configuration items from all of the files.
	 *
	 * @return array
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
		
		/** @noinspection PhpUndefinedMethodInspection */
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
