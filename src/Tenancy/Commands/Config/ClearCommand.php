<?php

namespace Boparaiamrit\Tenancy\Commands\Config;


use Boparaiamrit\Tenancy\Commands\TTenancyCommand;
use Boparaiamrit\Tenancy\Models\Host;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\ConfigClearCommand;

class ClearCommand extends ConfigClearCommand
{
	use TTenancyCommand;
	
	protected $name = 'config:clear';
	
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
			
			$directory = $this->getCachedConfigDirectory($hostname);
			if ($this->files->isDirectory($directory)) {
				$this->files->deleteDirectory($directory);
			}
			
			$this->info(sprintf('%s configuration\'s cleared successfully.', str_studly($hostname)));
		}
	}
	
	private function getCachedConfigDirectory($hostname)
	{
		/** @noinspection PhpUndefinedMethodInspection */
		return $this->laravel->bootstrapPath() . '/cache/' . $hostname;
	}
}
