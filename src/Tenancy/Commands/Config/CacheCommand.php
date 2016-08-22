<?php

namespace Boparaiamrit\Tenancy\Commands\Config;


use Boparaiamrit\Tenancy\Bootstrap\Configuration;
use Boparaiamrit\Tenancy\Contracts\HostRepositoryContract;
use Boparaiamrit\Tenancy\Models\Host;
use Boparaiamrit\Tenancy\Traits\DatabaseCommandTrait;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\ConfigCacheCommand;

class CacheCommand extends ConfigCacheCommand
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
			$this->call('config:clear');
			
			/** @var HostRepositoryContract|Host $Host */
			$config = $this->getFreshCustomerConfiguration($Host->identifier);
			
			$directory = $this->getCachedConfigDirectory($Host->identifier);
			if (!$this->files->isDirectory($directory)) {
				$this->files->makeDirectory($directory);
			}
			
			$this->files->put(
				$this->getCachedConfigPath($Host->identifier), '<?php return ' . var_export($config, true) . ';' . PHP_EOL
			);
		}
		
		$this->info('Configuration cached successfully!');
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
	
	private function getCachedConfigPath($customer)
	{
		return $this->getCachedConfigDirectory($customer) . '/config.php';
	}
	
	/**
	 * Boot a fresh copy of the application configuration.
	 *
	 * @param $customer
	 *
	 * @return array
	 */
	protected function getFreshCustomerConfiguration($customer)
	{
		/** @noinspection PhpUndefinedMethodInspection */
		$path = $this->laravel->bootstrapPath() . '/app.php';
		
		/** @noinspection PhpIncludeInspection */
		$app = require $path;
		
		$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
		
		(new Configuration($customer))->reload();
		
		/** @noinspection PhpUndefinedMethodInspection */
		return $app['config']->all();
	}
}
