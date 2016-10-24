<?php

namespace Boparaiamrit\Tenancy\Commands\Config;


use Boparaiamrit\Tenancy\Commands\TTenancyCommand;
use Boparaiamrit\Tenancy\Models\Host;
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
		$Hosts = $this->getHosts();
		
		foreach ($Hosts as $Host) {
			/** @var Host $Host */
			$hostname = $Host->identifier;
			
			$this->call('config:clear', ['--hostname' => $hostname]);
			
			array_set($GLOBALS, 'hostname', $hostname);
			
			$config    = $this->getFreshCustomerConfiguration();
			$directory = $this->getCachedConfigDirectory($hostname);
			if (!$this->files->isDirectory($directory)) {
				$this->files->makeDirectory($directory);
			}
			
			$this->files->put(
				$this->getCachedConfigPath($hostname), '<?php return ' . var_export($config, true) . ';' . PHP_EOL
			);
			
			$this->info(sprintf('%s configuration\'s cached successfully.', str_studly($hostname)));
		}
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
