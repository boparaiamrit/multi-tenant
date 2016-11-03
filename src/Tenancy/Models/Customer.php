<?php

namespace Boparaiamrit\Tenancy\Models;


use Boparaiamrit\Tenancy\Presenters\CustomerPresenter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property int        $id
 * @property string     $name
 * @property string     $email
 * @property string     $twitter_handle
 * @property string     $website
 * @property Collection $hosts
 * @property Carbon     $created_at
 * @property Carbon     $updated_at
 * @property Carbon     $deleted_at
 * @property string     $logo
 * @property string     $banner
 * @property string     $phone
 */
class Customer extends BaseModel
{
	const COLLECTION = 'customers';
	
	const NAME           = 'name';
	const EMAIL          = 'email';
	const TWITTER_HANDLE = 'twitter_handle';
	const WEBSITE        = 'website';
	
	protected $collection = self::COLLECTION;
	
	protected $presenter = CustomerPresenter::class;
	
	protected $fillable = [self::NAME, self::EMAIL, self::TWITTER_HANDLE, self::WEBSITE];
	
	/**
	 * All hosts of this customer.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function hosts()
	{
		return $this->hasMany(Host::class);
	}
}
