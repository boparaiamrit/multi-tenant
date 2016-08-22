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
        {--customer= : Name of the first customer}
        {--email= : Email address of the first customer}
        {--hostname= : Domain- or hostname for the first customer website}
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
		
		$this->Helper = new ServerConfigurationHelper();
	}
	
	/**
	 * Handles the set up.
	 */
	public function handle()
	{
		$this->configuration = config('webserver');
		
		$name       = $this->option('customer');
		$email      = $this->option('email');
		$hostname   = $this->option('hostname');
		$identifier = $this->option('identifier');
		
		if (empty($name)) {
			$name = $this->ask('Please provide a customer name or restart command with --customer');
		}
		
		if (empty($email)) {
			$email = $this->ask('Please provide a customer email address or restart command with --email');
		}
		
		if (empty($hostname)) {
			$hostname = $this->ask('Please provide a customer hostname or restart command with --hostname');
		}
		
		if (!empty($identifier) && strlen($identifier) > 100) {
			$identifier = $this->ask('Please provide an identifier with a max length of 10 or restart command with --identifier');
		}
		
		$this->comment('Welcome to boparaiamrit multitenancy.');
		$this->Helper->createDirectories();
		
		// Create the Customer configurations
		
		/** @var Customer $Customer */
		$Customer = $this->Customer->create(compact('name', 'email'));
		
		if (empty($identifier)) {
			$identifier = str_limit(str_replace(['.'], '', $hostname), 100, '');
		}
		
		/** @var Host $Host */
		$Host = $this->Host->create([
			'hostname'    => $hostname,
			'identifier'  => $identifier,
			'customer_id' => $Customer->id,
		]);
		
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
		
		if ($Customer->exists && $Host->exists) {
			$this->info('Configuration successful');
		}
	}
}
