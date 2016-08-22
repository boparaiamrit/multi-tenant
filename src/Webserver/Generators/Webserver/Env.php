<?php

namespace Boparaiamrit\Webserver\Generators\Webserver;


use Boparaiamrit\Webserver\Generators\AbstractFileGenerator;

class Env extends AbstractFileGenerator
{
	/**
	 * Generates the view that is written.
	 *
	 * @return \Illuminate\View\View
	 */
	public function generate()
	{
		$config = [
			'Host'     => $this->Host,
			'Customer' => $this->Host->customer
		];
		
		return view('webserver::env.configuration', $config);
	}
	
	/**
	 * Provides the complete path to publish the generated content to.
	 *
	 * @return string
	 */
	protected function publishPath()
	{
		return sprintf('%s.%s.env', config('webserver.env.path'), $this->name());
	}
	
	/**
	 * Reloads service if possible.
	 *
	 * @return bool
	 */
	protected function serviceReload()
	{
		return false;
	}
}
