<?php

namespace Boparaiamrit\Webserver\Commands;


use Boparaiamrit\Framework\Commands\AbstractCommand;
use Boparaiamrit\Tenancy\Contracts\CertificateRepositoryContract;
use Boparaiamrit\Tenancy\Models\Certificate;
use Boparaiamrit\Webserver\Generators\Webserver\SSL;

class CertificateCommand extends AbstractCommand
{
	
	/**
	 * @var Certificate
	 */
	protected $certificate;
	
	/**
	 * @var string
	 */
	protected $action;
	
	/**
	 * Create a new command instance.
	 *
	 * @param        $certificate_id
	 * @param string $action
	 */
	public function __construct($certificate_id, $action = 'update')
	{
		parent::__construct();
		
		$this->certificate = app(CertificateRepositoryContract::class)->findById($certificate_id);
		
		$this->action = $action;
	}
	
	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function fire()
	{
		if (!in_array($this->action, ['create', 'update', 'delete'])) {
			return;
		}
		
		$action = sprintf('on%s', ucfirst($this->action));
		
		(new SSL($this->getCertificate()))->{$action}();
	}
	
	/**
	 * @return Certificate|CertificateRepositoryContract
	 */
	public function getCertificate()
	{
		return $this->certificate;
	}
}
