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
		{--hostname= : Domain- or hostname for the the Customer website}
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
	 * @var int
	 */
	protected $step = 1;
	
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
		$hostname      = $this->option('hostname');
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
		
		if (empty($hostname)) {
			$hostname = $this->ask('Please provide a customer hostname or restart command with --hostname');
		}
		
		if (!empty($identifier) && strlen($identifier) > 100) {
			$identifier = $this->ask('Please provide an identifier with a max length of 10 or restart command with --identifier');
		}
		
		// Seed DB with Local Data
		$this->info('Multitenancy Setup');
		
		// Create the Customer configurations
		$Customer = $this->createCustomer($name, $email, $twitterHandle, $website);
		
		// Create Host
		$Host = $this->createHost($Customer, $identifier, $hostname);
		
		// Seed DB with Local Data
		$this->call('db:seed', ['--hostname' => $hostname]);
		
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
	 * @param $hostname
	 *
	 * @return Host
	 */
	private function createHost($Customer, $identifier, $hostname)
	{
		if (empty($identifier)) {
			$identifier = hostname_cleaner($hostname);
		}
		
		/** @noinspection PhpUndefinedFieldInspection */
		/** @var Host $Host */
		$Host = $this->Host
			->Model->firstOrNew([
				Host::HOSTNAME    => $hostname,
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
