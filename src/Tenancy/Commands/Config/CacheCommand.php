<?php

namespace Hyn\Tenancy\Commands\Config;


use Dotenv\Dotenv;
use Hyn\Tenancy\Bootstrap\Configuration;
use Hyn\Tenancy\Models\Website;
use Hyn\Tenancy\Traits\TenantDatabaseCommandTrait;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\ConfigCacheCommand;

class CacheCommand extends ConfigCacheCommand
{
	use TenantDatabaseCommandTrait;
	/**
	 * @var \Hyn\Tenancy\Contracts\WebsiteRepositoryContract|Website
	 */
	protected $Website;
	
	/**
	 * SeedCommand constructor.
	 *
	 * @param Filesystem $files
	 *
	 */
	public function __construct(Filesystem $files)
	{
		parent::__construct($files);
		$this->Website = app('Hyn\Tenancy\Contracts\WebsiteRepositoryContract');
	}
	
	/**
	 * Fires the command.
	 */
	public function fire()
	{
		// if no tenant option is set, simply run the native laravel seeder
		if (!$this->option('tenant')) {
			$this->error('No Tenant Provided.');
			die;
		}
		
		$websites = $this->getWebsitesFromOption();
		
		foreach ($websites as $website) {
//			$this->call('config:clear');
			
			/** @var Website $website */
			$config = $this->getFreshTenantConfiguration($website->identifier);
			
			$directory = $this->getCachedConfigDirectory($website->identifier);
			if (!$this->files->isDirectory($directory)) {
				$this->files->makeDirectory($directory);
			}
			
			$this->files->put(
				$this->getCachedConfigPath($website->identifier), '<?php return ' . var_export($config, true) . ';' . PHP_EOL
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
			$this->getTenantOption()
		);
	}
	
	private function getCachedConfigDirectory($tenant)
	{
		return $this->laravel->bootstrapPath() . '/cache/' . $tenant;
	}
	
	private function getCachedConfigPath($tenant)
	{
		return $this->getCachedConfigDirectory($tenant) . '/config.php';
	}
	
	/**
	 * Boot a fresh copy of the application configuration.
	 *
	 * @param $tenant
	 *
	 * @return array
	 */
	protected function getFreshTenantConfiguration($tenant)
	{
		$app = require $this->laravel->bootstrapPath() . '/app.php';
		
		$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
		
		(new Configuration($tenant))->reload();
		
		/** @noinspection PhpUndefinedMethodInspection */
		return $app['config']->all();
	}
}
