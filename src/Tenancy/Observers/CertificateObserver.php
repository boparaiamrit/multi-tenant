<?php

namespace Boparaiamrit\Tenancy\Observers;


use Boparaiamrit\Tenancy\Models\Host;
use Boparaiamrit\Webserver\Commands\CertificateCommand;
use Illuminate\Foundation\Bus\DispatchesJobs;

class CertificateObserver
{
	use DispatchesJobs;
	
	/**
	 * @param \Boparaiamrit\Tenancy\Models\Certificate $Model
	 */
	public function creating($Model)
	{
		foreach (['certificate', 'authority_bundle', 'key'] as $attribute) {
			if ($Model->{$attribute}) {
				$Model->{$attribute} = trim($Model->{$attribute});
			}
		}
		
		if ($Model->x509) {
			$Model->validates_at   = $Model->x509->getValidityFrom();
			$Model->invalidates_at = $Model->x509->getValidityTo();
			$Model->wildcard       = $Model->x509->isWildcard();
		}
	}
	
	/**
	 * @param \Boparaiamrit\Tenancy\Models\Certificate $Model
	 */
	public function created($Model)
	{
		if ($Model->x509) {
			foreach ($Model->x509->getHostnames() as $hostname) {
				$Hostname                       = new Host();
				$Hostname->certificate_id       = $Model->id;
				$Hostname->certificate_hostname = $hostname;
				$Hostname->save();
			}
		}
		$this->dispatch(
			new CertificateCommand($Model->id, 'create')
		);
	}
	
	public function updated($Model)
	{
		$this->dispatch(
			new CertificateCommand($Model->id, 'update')
		);
	}
	
	public function deleting($Model)
	{
		$this->dispatch(
			new CertificateCommand($Model->id, 'delete')
		);
	}
}
