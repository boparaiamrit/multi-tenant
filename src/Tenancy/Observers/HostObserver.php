<?php

namespace Boparaiamrit\Tenancy\Observers;


use Boparaiamrit\Tenancy\Models\Host;
use Boparaiamrit\Webserver\Commands\WebserverCommand;

class HostObserver
{
	/**
	 * @param Host $Host
	 */
	public function created($Host)
	{
		(new WebserverCommand($Host, 'create'))->fire();
	}
	
	/**
	 * @param Host $Host
	 */
	public function updated($Host)
	{
		(new WebserverCommand($Host, 'update'))->fire();
	}
	
	/**
	 * @param Host $Host
	 */
	public function deleting($Host)
	{
		(new WebserverCommand($Host, 'delete'))->fire();
	}
}
