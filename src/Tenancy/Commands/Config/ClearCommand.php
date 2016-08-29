<?php

namespace Boparaiamrit\Tenancy\Commands\Config;


use Boparaiamrit\Tenancy\Commands\TTenancyCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\ConfigClearCommand;

class ClearCommand extends ConfigClearCommand
{
	use TTenancyCommand;
	
	protected $signature = 'config:clear {--host=default}';
	
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
		
		$directory = $this->getCachedConfigDirectory($Host->identifier);
		if ($this->files->isDirectory($directory)) {
			$this->files->deleteDirectory($directory);
		}
		
		$this->info('Configuration cache cleared!');
	}
	
	private function getCachedConfigDirectory($hostname)
	{
		/** @noinspection PhpUndefinedMethodInspection */
		return $this->laravel->bootstrapPath() . '/cache/' . $hostname;
	}
}
