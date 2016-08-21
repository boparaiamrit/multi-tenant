<?php

namespace Boparaiamrit\Tenancy\Presenters;


use Boparaiamrit\Framework\Presenters\AbstractModelPresenter;
use Boparaiamrit\Tenancy\Models\Host;

class HostPresenter extends AbstractModelPresenter
{
	/**
	 * @return mixed
	 */
	public function name()
	{
		/** @var Host $this */
		return $this->hostname;
	}
}
