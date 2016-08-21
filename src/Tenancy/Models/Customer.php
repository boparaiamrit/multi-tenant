<?php

namespace Boparaiamrit\Tenancy\Models;


use Boparaiamrit\Tenancy\Presenters\CustomerPresenter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property int        $id
 * @property string     $name
 * @property string     $email
 * @property Collection $hosts
 * @property Carbon     $created_at
 * @property Carbon     $updated_at
 * @property Carbon     $deleted_at
 */
class Customer extends BaseModel
{
	const NAME  = 'name';
	const EMAIL = 'email';
	
	protected $collection = 'customers';
	
	protected $presenter = CustomerPresenter::class;
	
	protected $fillable = [self::NAME, self::EMAIL];
	
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
