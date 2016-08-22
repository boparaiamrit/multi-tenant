<?php

namespace Boparaiamrit\Webserver\Commands;


use Boparaiamrit\Framework\Commands\AbstractRootCommand;
use Boparaiamrit\Tenancy\Contracts\HostRepositoryContract;
use Boparaiamrit\Tenancy\Models\Host;
use Boparaiamrit\Webserver\Generators\Webserver\Env;
use Boparaiamrit\Webserver\Generators\Webserver\Fpm;
use Boparaiamrit\Webserver\Generators\Webserver\Nginx;
use Boparaiamrit\Webserver\Generators\Webserver\SSL;
use Boparaiamrit\Webserver\Generators\Webserver\Supervisor;

class WebserverCommand extends AbstractRootCommand
{
	protected $name = 'webserver';
	
	/**
	 * @var Host
	 */
	protected $Host;
	
	/**
	 * @var string
	 */
	protected $action;
	
	/**
	 * Create a new command instance.
	 *
	 * @param        $HostId
	 * @param string $action
	 *
	 */
	public function __construct($HostId, $action = 'update')
	{
		parent::__construct();
		
		$this->setHost($HostId);
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
		
		if(!empty($this->Host->certificate_id)) {
			(new SSL($this->Host->certificate))->onUpdate();
		}
		
		// Php FPM
		(new Fpm($this->Host))->{$action}();
		
		// Webservers
		(new Nginx($this->Host))->{$action}();
		
		// Supervisor
		(new Supervisor($this->Host))->{$action}();
		
		// Env
		(new Env($this->Host))->{$action}();
	}
	
	
	public function setHost($HostId)
	{
		/** @var Host $Host */
		$Host = app(HostRepositoryContract::class)->findById($HostId);
		
		$this->Host = $Host;
	}
	
	/**
	 * @param string $action
	 */
	public function setAction($action)
	{
		$this->action = $action;
	}
}
