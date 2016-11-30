<?php

namespace Boparaiamrit\Tenancy\Commands;


use Boparaiamrit\Tenancy\Models\Host;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\Console\Input\InputOption;

trait TTenancyCommand
{
	/**
	 * @return Collection
	 */
	protected function getHosts()
	{
		$hostname = $this->option('hostname');
		
		if ($hostname == 'all') {
			/** @var Collection $Hosts */
			$Hosts = Host::all();
		} else {
			$hostname = hostname_cleaner($hostname);
			
			/** @var Collection $Hosts */
			$Hosts = Host::where('hostname', $hostname)
						 ->orWhere('identifier', $hostname)
						 ->get();
			
			if ($Hosts->isEmpty()) {
				$this->error('Hostname not found');
				exit;
			}
		}
		
		return $Hosts;
	}
	
	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		$hostname = config('env.default_host');
		
		/** @noinspection PhpUndefinedMethodInspection */
		/** @noinspection PhpUndefinedClassInspection */
		return array_merge(parent::getOptions(), [
			['hostname', null, InputOption::VALUE_OPTIONAL, 'The hostname(s) to apply on; use {all|hostname}', $hostname]
		]);
	}
}
