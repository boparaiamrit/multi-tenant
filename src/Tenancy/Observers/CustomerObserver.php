<?php

namespace Boparaiamrit\Tenancy\Observers;


use Boparaiamrit\Tenancy\Models\Customer;

class CustomerObserver
{
	public function deleting(Customer $Model)
	{
		foreach ($Model->hosts as $Host) {
			$Host->delete();
		}
	}
}
