<?php

namespace Hyn\Webserver\Commands;


use Cache;
use Hyn\Framework\Commands\AbstractRootCommand;
use Hyn\Tenancy\Models\Website;
use Hyn\Webserver\Generators\Database\Database;
use Hyn\Webserver\Generators\Unix\WebsiteUser;
use Hyn\Webserver\Generators\Webserver\Apache;
use Hyn\Webserver\Generators\Webserver\Fpm;
use Hyn\Webserver\Generators\Webserver\Nginx;
use Hyn\Webserver\Generators\Webserver\Ssl;

class WebserverCommand extends AbstractRootCommand
{
	protected $name = 'webserver';
	
	/**
	 * @var Website
	 */
	protected $website;
	
	/**
	 * @var string
	 */
	protected $action;
	
	/**
	 * Create a new command instance.
	 *
	 * @param int    $website_id
	 * @param string $action
	 */
	public function __construct($website_id, $action = 'update')
	{
		parent::__construct();
		
		$this->setWebsite($website_id);
		$this->setAction($action);
	}
	
	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		if (!in_array($this->action, ['create', 'update', 'delete'])) {
			return;
		}
		
		$action = sprintf('on%s', ucfirst($this->action));
		
		foreach ($this->website->hostnamesWithCertificate as $hostname) {
			(new Ssl($hostname->certificate))->onUpdate();
		}
		
		if (config('webserver.user') === true) {
			(new WebsiteUser($this->website))->{$action}();
		}
		
		// Php fpm
		(new Fpm($this->website))->{$action}();
		
		// Webservers
		$webserver = Cache::get('webserver:option', 'nginx');
		if ($webserver == 'apache') {
			(new Apache($this->website))->{$action}();
		} else if ($webserver == 'nginx') {
			(new Nginx($this->website))->{$action}();
		}
		
		(new Database($this->website))->{$action}();
	}
	
	
	public function setWebsite($websiteId)
	{
		/** @var Website $website */
		$website = app('Hyn\Tenancy\Contracts\WebsiteRepositoryContract')->findById($websiteId);
		
		$this->website = $website;
	}
	
	/**
	 * @param string $action
	 */
	public function setAction($action)
	{
		$this->action = $action;
	}
}
