<?php

namespace Boparaiamrit\Tenancy\Jobs;


use Boparaiamrit\Webserver\Generators\Webserver\Env;
use Boparaiamrit\Webserver\Generators\Webserver\Fpm;
use Boparaiamrit\Webserver\Generators\Webserver\Nginx;
use Boparaiamrit\Webserver\Generators\Webserver\Supervisor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Jenssegers\Mongodb\Eloquent\Model;

class WebserverJob implements ShouldQueue
{
	use InteractsWithQueue, Queueable;
	
	/**
	 * @var Model
	 */
	protected $Host;
	
	/**
	 * @var Model
	 */
	protected $user = null;
	
	public function __construct($Host, $user = [])
	{
		$this->Host = $Host;
		$this->user = $user;
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
		
		app(Kernel::class)->call('db:seed', [
			'--force' => true, '--hostname' => $this->Host->identifier
		]);
		
		app(Kernel::class)->call('setup:admin', [
			'--email' => array_get($this->user, 'email'),
			'--name'  => array_get($this->user, 'name'),
			'--hostname' => $this->Host->hostname
		]);
		
		app(Kernel::class)->call('setup:email', [
			'--email' => array_get($this->user, 'email'),
			'--hostname' => $this->Host->hostname
		]);
		
	}

}