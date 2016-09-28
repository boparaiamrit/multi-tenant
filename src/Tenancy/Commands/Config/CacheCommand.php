<?php

namespace Boparaiamrit\Tenancy\Commands\Config;


use Boparaiamrit\Tenancy\Commands\TTenancyCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\ConfigCacheCommand;

class CacheCommand extends ConfigCacheCommand
{
	use TTenancyCommand;
	
	protected $name = 'config:cache';
	
	/**
	 * SeedCommand constructor.
	 *
	 * @param Filesystem $files
	 *
	 */
	public function __construct(Filesystem $files)
	{
		parent::__construct($files);
	}
	
	/**
	 * Fires the command.
	 */
	public function fire()
	{
		$Host = $this->getHost();
		
		array_set($GLOBALS, 'hostname', $Host->identifier);
		
		$this->call('config:clear');
		
		$config    = $this->getFreshCustomerConfiguration();
		$directory = $this->getCachedConfigDirectory($Host->identifier);
		if (!$this->files->isDirectory($directory)) {
			$this->files->makeDirectory($directory);
		}
		
		$this->files->put(
			$this->getCachedConfigPath($Host->identifier), '<?php return ' . var_export($config, true) . ';' . PHP_EOL
		);
		
		$this->info('Configuration cached successfully!');
	}
	
	private function getCachedConfigDirectory($hostname)
	{
		/** @noinspection PhpUndefinedMethodInspection */
		return $this->laravel->bootstrapPath() . '/cache/' . $hostname;
	}
	
	private function getCachedConfigPath($hostname)
	{
		return $this->getCachedConfigDirectory($hostname) . '/config.php';
	}
	
	protected function getFreshCustomerConfiguration()
	{
		/** @noinspection PhpUndefinedMethodInspection */
		$path = $this->laravel->bootstrapPath() . '/app.php';
		
		/** @noinspection PhpIncludeInspection */
		$app = require $path;
		
		$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
		
		/** @noinspection PhpUndefinedMethodInspection */
		return $app['config']->all();
	}
}
