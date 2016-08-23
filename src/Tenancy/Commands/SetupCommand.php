<?php

namespace Boparaiamrit\Tenancy\Commands;


use Boparaiamrit\Tenancy\Contracts\CustomerRepositoryContract;
use Boparaiamrit\Tenancy\Contracts\HostRepositoryContract;
use Boparaiamrit\Tenancy\Models\Customer;
use Boparaiamrit\Tenancy\Models\Host;
use Boparaiamrit\Webserver\Helpers\ServerConfigurationHelper;
use Illuminate\Console\Command;

class SetupCommand extends Command
{
	/**
	 * @var string
	 */
	protected $signature = 'multitenant:setup
        {--customer= : Name of the the Customer}
        {--email= : Email address of the the Customer}
        {--twitter_handle= : Twitter Handle of the the Customer}
        {--website= : Website address of the the Customer}
        {--hostname= : Domain- or hostname for the the Customer website}
        {--webserver= : Hook into webserver (nginx|no)}
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
	 * @var ServerConfigurationHelper
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
		$this->Helper   = new ServerConfigurationHelper();
		
		$this->configuration = config('webserver');
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
		
		$this->comment('Welcome to Boparaiamrit Multitenancy.');
		$this->Helper->createDirectories();
		
		// Create the Customer configurations
		$Customer = $this->createCustomer($name, $email, $twitterHandle, $website);
		
		// Create Host
		$Host = $this->createHost($Customer, $identifier, $hostname);
		
		$webserver = $this->option('webserver');
		
		if (empty($webserver)) {
			$webserver = $this->anticipate('Integrate into a webserver?', ['no', 'apache', 'nginx'], 'no');
		}
		
		if ($webserver != 'no') {
			$webserverConfiguration = array_get($this->configuration, $webserver);
			$webserverClass         = array_get($webserverConfiguration, 'class');
		} else {
			$webserver = 'Nginx';
		}
		
		// hook into the webservice of choice once object creation succeeded
		if ($webserver) {
			/** @noinspection PhpUndefinedMethodInspection */
			/** @noinspection PhpUndefinedVariableInspection */
			(new $webserverClass($Host))->register();
		}
		
		$this->call('db:seed', ['--host' => $Host->identifier]);
		
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
		$Customer = $this->Customer->Model->firstOrNew([
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
			$identifier = str_limit(str_replace(['.'], '', $hostname), 100, '');
		}
		
		
		/** @noinspection PhpUndefinedFieldInspection */
		/** @var Host $Host */
		$Host = $this->Host->Model->firstOrNew([
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
