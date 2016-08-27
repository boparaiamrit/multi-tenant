<?php

namespace Boparaiamrit\Webserver\Generators\Webserver;


use Boparaiamrit\Tenancy\Models\Certificate;
use Boparaiamrit\Webserver\Abstracts\AbstractGenerator;

class SSL extends AbstractGenerator
{
	/**
	 * @var Certificate
	 */
	protected $certificate;
	
	public function __construct(Certificate $certificate)
	{
		$this->certificate = $certificate;
	}
	
	/**
	 * @return string
	 */
	public function name()
	{
		return $this->certificate->present()->name;
	}
	
	/**
	 * @param string $from
	 * @param string $to
	 *
	 * @return bool
	 */
	public function onRename($from, $to)
	{
		// no action required
		return true;
	}
	
	/**
	 * Publish path for specific filetype.
	 *
	 * @param string $postfix
	 *
	 * @return string
	 */
	protected function publishPath($postfix = 'key')
	{
		return $this->certificate->publishPath($postfix);
	}
	
	/**
	 * Pem.
	 *
	 * @return string
	 */
	protected function pem()
	{
		return implode("\r\n", [$this->certificate->certificate, $this->certificate->authority_bundle]);
	}
	
	/**
	 * @return bool
	 */
	public function onCreate()
	{
		$File = app('files');
		
		return
			(!$File->isDirectory(dirname($this->certificate->pathKey)) && $File->makeDirectory(dirname($this->certificate->pathKey)))
			&& $File->put($this->certificate->pathKey, $this->certificate->key)
			&& $File->put($this->certificate->pathCrt, $this->certificate->certificate)
			&& $File->put($this->certificate->pathCa, $this->certificate->authority_bundle)
			&& $File->put($this->certificate->pathPem, $this->pem());
	}
	
	/**
	 * @return bool
	 */
	public function onUpdate()
	{
		$this->onCreate();
	}
	
	/**
	 * @return bool
	 */
	public function onDelete()
	{
		$File = app('files');
		
		return
			$File->delete($this->publishPath('key'))
			&& $File->delete($this->publishPath('pem'));
	}
}