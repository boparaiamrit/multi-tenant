<?php

namespace Boparaiamrit\Tenancy\Commands;


use Boparaiamrit\Tenancy\Models\Host;
use Boparaiamrit\Webserver\Generators\Webserver\Env;
use Boparaiamrit\Webserver\Generators\Webserver\Fpm;
use Boparaiamrit\Webserver\Generators\Webserver\Nginx;
use Boparaiamrit\Webserver\Generators\Webserver\Supervisor;
use Boparaiamrit\Webserver\Helpers\ServerHelper;
use Illuminate\Console\Command;

class SetupCommand extends Command
{
	/**
	 * @var string
	 */
	protected $signature = 'multitenant:setup
		{--domain= : Domain or domain for the the Customer website}
        {--identifier= : Website identifier}';
	
	/**
	 * @var string
	 */
	protected $description = 'Final configuration step for boparaiamrit multitenancy packages.';
	
	/**
	 * Handles the set up.
	 */
	public function handle()
	{
		ServerHelper::createDirectories();
		
		$domain     = $this->option('domain');
		$identifier = $this->option('identifier');
		
		if (empty($domain)) {
			$domain = $this->ask('Please provide a customer domain or restart command with --domain');
		}
		
		if (!empty($identifier) && strlen($identifier) > 100) {
			$identifier = $this->ask('Please provide an identifier with a max length of 10 or restart command with --identifier');
		}
		
		// Seed DB with Local Data
		$this->info('Multitenancy Setup');
		
		// Create Host
		$Host = $this->createHost($identifier, $domain);
		
		// Php FPM
		(new Fpm($Host))->onCreate();
		// Supervisor
		(new Supervisor($Host))->onCreate();
		// Webservers
		(new Nginx($Host))->onCreate();
		// Env
		(new Env($Host))->onCreate();
		
		// Seed DB with Local Data
		$this->call('db:seed', ['--force' => true, '--hostname' => $Host->identifier]);
		
		if ($Host->exists) {
			$this->info('Configuration successful.');
		}
	}
	
	/**
	 * @param $identifier
	 * @param $domain
	 *
	 * @return Host
	 */
	private function createHost($identifier, $domain)
	{
		if (empty($identifier)) {
			$identifier = hostname_cleaner($domain);
		}
		
		/** @noinspection PhpUndefinedFieldInspection */
		/** @var Host $Host */
		$Host = Host::firstOrNew([
			Host::HOSTNAME   => $domain,
			Host::IDENTIFIER => $identifier
		]);
		
		$Host->save();
		
		return $Host;
	}
}
