<?php

namespace Boparaiamrit\Tenancy\Jobs;


use Boparaiamrit\Webserver\Generators\Webserver\Env;
use Boparaiamrit\Webserver\Generators\Webserver\Fpm;
use Boparaiamrit\Webserver\Generators\Webserver\Nginx;
use Boparaiamrit\Webserver\Generators\Webserver\Supervisor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WebserverJob implements ShouldQueue
{
	use SerializesModels, InteractsWithQueue, Queueable;
	
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
		
		// Seed DB with Local Data
		app(Kernel::class)->call('db:seed', [
			'--force' => true, '--hostname' => $this->Host->identifier
		]);
	}
}
