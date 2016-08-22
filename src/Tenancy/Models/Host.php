<?php

namespace Boparaiamrit\Tenancy\Models;


use Boparaiamrit\Tenancy\Presenters\HostPresenter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property string      $id
 * @property string      $identifier
 * @property string      $hostname
 * @property bool        $is_secure
 * @property string      $redirect_to
 * @property string      $customer_id
 * @property string      $certificate_id
 * @property string      $certificate_hostname
 * @property Customer    $customer
 * @property Host        $redirectToHost
 * @property Certificate $certificate
 * @property Collection  $withCertificate
 * @property Collection  $withoutCertificate
 * @property Carbon      $deleted_at
 */
class Host extends BaseModel
{
	const IDENTIFIER           = 'identifier';
	const HOSTNAME             = 'hostname';
	const IS_SECURE            = 'is_secure';
	const REDIRECT_TO          = 'redirect_to';
	const CUSTOMER_ID          = 'customer_id';
	const CERTIFICATE_ID       = 'certificate_id';
	const CERTIFICATE_HOSTNAME = 'certificate__hostname';
	
	protected $collection = 'hosts';
	
	protected $presenter = HostPresenter::class;
	
	protected $fillable = [self::HOSTNAME, self::IDENTIFIER, self::CUSTOMER_ID];
	
	/**
	 * The customer who owns this hostname.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function customer()
	{
		return $this->belongsTo(Customer::class);
	}
	
	/**
	 * Host to redirect to.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function redirectToHost()
	{
		return $this->belongsTo(static::class, self::REDIRECT_TO);
	}
	
	/**
	 * Certificate this hostname uses.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function certificate()
	{
		return $this->belongsTo(Certificate::class, self::CERTIFICATE_ID);
	}
	
	/**
	 * Load all hostnames that have a certificate.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getWithCertificateAttribute()
	{
		/** @noinspection PhpUndefinedMethodInspection */
		return $this->whereNotNull(self::CERTIFICATE_ID)->get();
	}
	
	/**
	 * Loads all hostnames that have no certificate.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getWithoutCertificateAttribute()
	{
		/** @noinspection PhpUndefinedMethodInspection */
		return $this->whereNull(self::CERTIFICATE_ID)->get();
	}
	
	/**
	 * Identifies whether a redirect is required for this hostname.
	 *
	 * @return \Illuminate\Http\RedirectResponse|false
	 */
	public function redirectActionRequired()
	{
		// force to new hostname
		if (!empty($this->redirect_to)) {
			return $this->redirectToHost->redirectActionRequired();
		}
		// figure out whether we need a redirect to https
		if ($this->is_secure && !request()->secure()) {
			return redirect()->secure(request()->path());
		}
		
		// if default hostname is loaded and this is not the default hostname
		if (request()->getHttpHost() != $this->hostname) {
			return redirect()->away("http://{$this->hostname}/" . (request()->path() == '/' ? null : request()->path()));
		}
		
		return false;
	}
}
