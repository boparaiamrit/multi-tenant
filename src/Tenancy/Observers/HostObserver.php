<?php

namespace Boparaiamrit\Tenancy\Observers;


use Boparaiamrit\Tenancy\Models\Host;
use Boparaiamrit\Webserver\Commands\WebserverCommand;
use Illuminate\Foundation\Bus\DispatchesJobs;

class HostObserver
{
	use DispatchesJobs;
	
	/**
	 * @param Host $Host
	 */
	public function created($Host)
	{
		$this->dispatch(
			new WebserverCommand($Host->id, 'create')
		);
	}
	
	/**
	 * @param Host $Host
	 */
	public function updated($Host)
	{
		$this->dispatch(
			new WebserverCommand($Host->id, 'update')
		);
	}
	
	/**
	 * @param Host $Host
	 */
	public function deleting($Host)
	{
		$this->dispatch(
			new WebserverCommand($Host->id, 'delete')
		);
	}
}
