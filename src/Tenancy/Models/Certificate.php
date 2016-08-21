<?php

namespace Boparaiamrit\Tenancy\Models;


use Boparaiamrit\Tenancy\Presenters\CertificatePresenter;
use Boparaiamrit\Webserver\Tools\CertificateParser;
use Cache;
use Jenssegers\Mongodb\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

/**
 * Class SslCertificate.
 *
 * @property mixed pathKey
 * @property mixed pathCrt
 * @property mixed pathCa
 * @property mixed pathPem
 * @property mixed key
 * @property mixed certificate
 * @property mixed authority_bundle
 * @property mixed invalidates_at
 * @property mixed id
 * @property mixed validates_at
 * @property mixed wildcard
 * @property mixed x509
 */
class Certificate extends Model
{
	use PresentableTrait;
	
	/**
	 * @var string
	 */
	protected $presenter = CertificatePresenter::class;
	
	/**
	 * @var array
	 */
	protected $fillable = ['customer_id', 'certificate', 'authority_bundle', 'key'];
	
	/**
	 * @var array
	 */
	protected $appends = ['pathKey', 'pathPem', 'pathCrt', 'pathCa'];
	
	/**
	 * @return array
	 */
	public function getDates()
	{
		return ['validates_at', 'invalidates_at'];
	}
	
	public function getIsExpired()
	{
		return $this->invalidates_at ? $this->invalidates_at->isPast() : null;
	}
	
	/**
	 * @return CertificateParser|null
	 */
	public function getX509Attribute()
	{
		if (!Cache::has('ssl-x509-' . $this->id)) {
			Cache::add('ssl-x509-' . $this->id, $this->certificate ? new CertificateParser($this->certificate) : null, 3600);
		}
		
		return Cache::get('ssl-x509-' . $this->id);
	}
	
	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function customer()
	{
		return $this->belongsTo(Customer::class);
	}
	
	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function hosts()
	{
		return $this->hasMany(Host::class);
	}
	
	/**
	 * @return string
	 */
	public function getPathKeyAttribute()
	{
		return $this->publishPath('key');
	}
	
	/**
	 * @param string $postfix
	 *
	 * @return string
	 */
	public function publishPath($postfix = 'key')
	{
		return sprintf('%s/%s/certificate.%s', config('webserver.ssl.path'), $this->id, $postfix);
	}
	
	/**
	 * @return string
	 */
	public function getPathPemAttribute()
	{
		return $this->publishPath('pem');
	}
	
	/**
	 * @return string
	 */
	public function getPathCrtAttribute()
	{
		return $this->publishPath('crt');
	}
	
	/**
	 * @return string
	 */
	public function getPathCaAttribute()
	{
		return $this->publishPath('ca');
	}
}
