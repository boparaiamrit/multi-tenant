<?php

namespace Boparaiamrit\Tenancy\Commands;


use Boparaiamrit\Tenancy\Contracts\CustomerRepositoryContract;
use Boparaiamrit\Tenancy\Contracts\HostRepositoryContract;
use Boparaiamrit\Tenancy\Models\Customer;
use Boparaiamrit\Tenancy\Models\Host;
use Boparaiamrit\Webserver\Helpers\ServerHelper;
use Illuminate\Console\Command;

class SetupCommand extends Command
{
	/**
	 * @var string
	 */
	protected $signature = 'multitenant:setup
		{--domain= : Domain or domain for the the Customer website}
        {--customer= : Name of the the Customer}
        {--email= : Email address of the the Customer}
        {--twitter_handle= : Twitter Handle of the the Customer}
        {--website= : Website address of the the Customer}
        {--identifier= : Website identifier}';
	
	/**
	 * @var string
	 */
	protected $description = 'Final configuration step for boparaiamrit multitenancy packages';
	
	/**
	 * @var HostRepositoryContract
	 */
	protected $Host;
	
	/**
	 * @var CustomerRepositoryContract
	 */
	protected $Customer;
	
	/**
	 * @var ServerHelper
	 */
	protected $Helper;
	
	/**
	 * @var array
	 */
	protected $configuration;
	
	/**
	 * @param CustomerRepositoryContract $Customer
	 * @param HostRepositoryContract     $Host
	 */
	public function __construct(
		CustomerRepositoryContract $Customer,
		HostRepositoryContract $Host
	)
	{
		parent::__construct();
		
		$this->Host     = $Host;
		$this->Customer = $Customer;
		
		$this->configuration = config('webserver');
		
		// Create Directory not have
		(new ServerHelper())->createDirectories();
	}
	
	/**
	 * Handles the set up.
	 */
	public function handle()
	{
		$name          = $this->option('customer');
		$email         = $this->option('email');
		$twitterHandle = $this->option('twitter_handle');
		$website       = $this->option('website');
		$domain        = $this->option('domain');
		$identifier    = $this->option('identifier');
		
		if (empty($name)) {
			$name = $this->ask('Please provide a customer name or restart command with --customer');
		}
		
		if (empty($email)) {
			$email = $this->ask('Please provide a customer email address or restart command with --email');
		}
		
		if (empty($twitterHandle)) {
			$twitterHandle = $this->ask('Please provide a customer twitter handle or restart command with --twitter_handle');
		}
		
		if (empty($website)) {
			$website = $this->ask('Please provide a customer website address or restart command with --website');
		}
		
		if (empty($domain)) {
			$domain = $this->ask('Please provide a customer domain or restart command with --domain');
		}
		
		if (!empty($identifier) && strlen($identifier) > 100) {
			$identifier = $this->ask('Please provide an identifier with a max length of 10 or restart command with --identifier');
		}
		
		// Seed DB with Local Data
		$this->info('Multitenancy Setup');
		
		// Create the Customer configurations
		$Customer = $this->createCustomer($name, $email, $twitterHandle, $website);
		
		// Create Host
		$Host = $this->createHost($Customer, $identifier, $domain);
		
		array_set($GLOBALS, 'customer', ['name' => $Customer->name, 'email' => $Customer->email]);
		
		// Seed DB with Local Data
		$this->call('db:seed', ['--force' => true, '--hostname' => $Host->identifier]);
		
		if ($Customer->exists && $Host->exists) {
			$this->info('Configuration successful.');
		}
	}
	
	/**
	 * @param $name
	 * @param $email
	 * @param $twitterHandle
	 * @param $website
	 *
	 * @return Customer
	 */
	private function createCustomer($name, $email, $twitterHandle, $website)
	{
		/** @var Customer $Customer */
		$Customer = $this->Customer
			->Model->firstOrNew([
				Customer::NAME  => $name,
				Customer::EMAIL => $email
			]);
		
		$Customer->website        = $website;
		$Customer->twitter_handle = $twitterHandle;
		$Customer->save();
		
		return $Customer;
	}
	
	/**
	 * @param $Customer
	 * @param $identifier
	 * @param $domain
	 *
	 * @return Host
	 */
	private function createHost($Customer, $identifier, $domain)
	{
		if (empty($identifier)) {
			$identifier = hostname_cleaner($domain);
		}
		
		/** @noinspection PhpUndefinedFieldInspection */
		/** @var Host $Host */
		$Host = $this->Host
			->Model->firstOrNew([
				Host::HOSTNAME    => $domain,
				Host::IDENTIFIER  => $identifier,
				Host::CUSTOMER_ID => $Customer->id
			]);
		
		if ($Host->exists) {
			$Host->touch();
		} else {
			$Host->save();
		}
		
		return $Host;
	}
}
