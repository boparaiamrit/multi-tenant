<?php

namespace Boparaiamrit\Webserver\Commands;


use Boparaiamrit\Framework\Commands\AbstractRootCommand;
use Boparaiamrit\Tenancy\Contracts\CertificateRepositoryContract;
use Boparaiamrit\Tenancy\Models\Certificate;
use Boparaiamrit\Webserver\Generators\Webserver\Ssl;
use Illuminate\Contracts\Queue\ShouldQueue;

class CertificateCommand extends AbstractRootCommand implements ShouldQueue
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
	public function handle()
	{
		if (!in_array($this->action, ['create', 'update', 'delete'])) {
			return;
		}
		
		$action = sprintf('on%s', ucfirst($this->action));
		
		(new Ssl($this->getCertificate()))->{$action}();
	}
	
	/**
	 * @return Certificate|CertificateRepositoryContract
	 */
	public function getCertificate()
	{
		return $this->certificate;
	}
}
