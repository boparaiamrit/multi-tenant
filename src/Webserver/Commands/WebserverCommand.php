<?php

namespace Hyn\Webserver\Commands;


use Hyn\Framework\Commands\AbstractRootCommand;
use Hyn\Tenancy\Models\Website;
use Hyn\Webserver\Generators\Webserver\Fpm;
use Hyn\Webserver\Generators\Webserver\Nginx;
use Hyn\Webserver\Generators\Webserver\SSL;

class WebserverCommand extends AbstractRootCommand
{
	protected $name = 'webserver';
	
	/**
	 * @var Website
	 */
	protected $Website;
	
	/**
	 * @var string
	 */
	protected $action;
	
	/**
	 * Create a new command instance.
	 *
	 * @param int    $Website_id
	 * @param string $action
	 */
	public function __construct($Website_id, $action = 'update')
	{
		parent::__construct();
		
		$this->setWebsite($Website_id);
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
		
		foreach ($this->Website->hostnamesWithCertificate as $hostname) {
			(new SSL($hostname->certificate))->onUpdate();
		}
		
		// Php FPM
		(new Fpm($this->Website))->{$action}();
		
		// Webservers
		(new Nginx($this->Website))->{$action}();
	}
	
	
	public function setWebsite($websiteId)
	{
		/** @var Website $Website */
		$Website = app('Hyn\Tenancy\Contracts\WebsiteRepositoryContract')->findById($websiteId);
		
		$this->Website = $Website;
	}
	
	/**
	 * @param string $action
	 */
	public function setAction($action)
	{
		$this->action = $action;
	}
}
