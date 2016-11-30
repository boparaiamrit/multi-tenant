<?php

namespace Boparaiamrit\Tenancy;


use Boparaiamrit\Webserver\Generators\Webserver\Env;
use Boparaiamrit\Webserver\Generators\Webserver\Fpm;
use Boparaiamrit\Webserver\Generators\Webserver\Nginx;
use Boparaiamrit\Webserver\Generators\Webserver\Supervisor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Promoto\Jobs\Job;

class Webserver extends Job implements ShouldQueue
{
	use SerializesModels;
	
	protected $Host;
	
	public function __construct($Host)
	{
		$this->Host = $Host;
		
		$this->onQueue('system');
	}
	
	public function handle()
	{
		$this->job->delete();
		
		// Php FPM
		(new Fpm($this->Host))->onCreate();
		// Supervisor
		(new Supervisor($this->Host))->onCreate();
		// Webservers
		(new Nginx($this->Host))->onCreate();
		// Env
		(new Env($this->Host))->onCreate();
	}
}
