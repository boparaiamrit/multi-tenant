<?php

namespace Hyn\Tenancy\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property int        $id
 * @property string     $identifier
 * @property int        $tenant_id
 * @property Collection $hostnames
 * @property Customer   $customer
 * @property Collection $hostnamesWithCertificate
 * @property Collection $hostnamesWithoutCertificate
 * @property array      $certificateIds
 * @property Carbon     $created_at
 * @property Carbon     $updated_at
 * @property Carbon     $deleted_at
 */
class Website extends BaseModel
{
	protected $presenter = 'Hyn\Tenancy\Presenters\WebsitePresenter';
	
	protected $fillable = ['tenant_id', 'identifier'];
	
	protected $appends = ['directory'];
	
	/**
	 * Load all hostnames that have a certificate.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getHostnamesWithCertificateAttribute()
	{
		/** @noinspection PhpUndefinedMethodInspection */
		return $this->hostnames()->whereNotNull('ssl_certificate_id')->get();
	}
	
	/**
	 * Loads all hostnames of this website.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function hostnames()
	{
		/** @noinspection PhpUndefinedMethodInspection */
		return $this->hasMany(Hostname::class)->with('certificate');
	}
	
	/**
	 * Loads all hostnames that have no certificate.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getHostnamesWithoutCertificateAttribute()
	{
		/** @noinspection PhpUndefinedMethodInspection */
		return $this->hostnames()->whereNull('ssl_certificate_id')->get();
	}
	
	/**
	 * Loads the unique id's from the certificates.
	 *
	 * @return array
	 */
	public function getCertificateIdsAttribute()
	{
		/** @noinspection PhpUndefinedMethodInspection */
		return array_unique($this->hostnames()->whereNotNull('ssl_certificate_id')->lists('ssl_certificate_id'));
	}
	
	
	/**
	 * The customer who owns this website.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function customer()
	{
		return $this->belongsTo(Customer::class);
	}
}
