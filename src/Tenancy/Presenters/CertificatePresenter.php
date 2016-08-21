<?php

namespace Boparaiamrit\Tenancy\Presenters;


use Boparaiamrit\Framework\Presenters\AbstractModelPresenter;

/**
 * @property mixed hosts
 * @property mixed X509
 * @property mixed invalidates_at
 */
class CertificatePresenter extends AbstractModelPresenter
{
	/**
	 * SSL Certificate does not really have a name.
	 *
	 * @return array
	 */
	public function urlArguments()
	{
		return [
			'id' => $this->id,
		];
	}
	
	/**
	 * Shows summary of hosts.
	 *
	 * @return string
	 */
	public function hostsSummary()
	{
		$hosts = $this->hosts->lists('hostname')->all();
		
		return implode(', ', array_splice($hosts, 0, 5));
	}
	
	/**
	 * @return int
	 */
	public function additionalHosts()
	{
		return count($this->hosts) - 5;
	}
	
	
	/**
	 * @return mixed
	 */
	public function name()
	{
		return sprintf('%s %s', $this->X509->issuer(), $this->X509->type());
	}
}
