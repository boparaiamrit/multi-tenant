<?php

namespace Boparaiamrit\Tenancy\Commands\Config;


use Boparaiamrit\Tenancy\Contracts\HostRepositoryContract;
use Boparaiamrit\Tenancy\Traits\DatabaseCommandTrait;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\ConfigClearCommand;

class ClearCommand extends ConfigClearCommand
{
	use DatabaseCommandTrait;
	/**
	 * @var HostRepositoryContract
	 */
	protected $Host;
	
	/**
	 * SeedCommand constructor.
	 *
	 * @param Filesystem $files
	 *
	 */
	public function __construct(Filesystem $files)
	{
		parent::__construct($files);
		
		$this->Host = app(HostRepositoryContract::class);
	}
	
	/**
	 * Fires the command.
	 */
	public function fire()
	{
		// if no tenant option is set, simply run the native laravel seeder
		if (!$this->option('customer')) {
			$this->error('No Customer Provided.');
			die;
		}
		
		$Hosts = $this->getHostsFromOption();
		
		foreach ($Hosts as $Host) {
			$directory = $this->getCachedConfigDirectory($Host->identifier);
			if (!$this->files->isDirectory($directory)) {
				$this->files->deleteDirectory($directory);
			}
		}
		
		$this->info('Configuration cache cleared!');
	}
	
	/**
	 * @return array
	 */
	protected function getOptions()
	{
		return array_merge(
			parent::getOptions(),
			$this->getCustomerOption()
		);
	}
	
	private function getCachedConfigDirectory($customer)
	{
		/** @noinspection PhpUndefinedMethodInspection */
		return $this->laravel->bootstrapPath() . '/cache/' . $customer;
	}
}
