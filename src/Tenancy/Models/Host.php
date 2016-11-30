<?php

namespace Boparaiamrit\Tenancy\Models;


use Carbon\Carbon;

/**
 * @property string   $id
 * @property string   $identifier
 * @property string   $hostname
 * @property string   $customer_id
 * @property Carbon   $deleted_at
 * @property Customer $customer
 */
class Host extends BaseModel
{
	const COLLECTION = 'hosts';
	
	const IDENTIFIER  = 'identifier';
	const HOSTNAME    = 'hostname';
	const CUSTOMER_ID = 'customer_id';
	
	protected $collection = self::COLLECTION;
	
	protected $fillable = [self::HOSTNAME, self::IDENTIFIER, self::CUSTOMER_ID];
	
	/**
	 * The customer who owns this hostname.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Customer
	 */
	public function customer()
	{
		return $this->belongsTo(Customer::class, self::CUSTOMER_ID);
	}
	
	public function getCustomers()
	{
		return Customer::all(['id', 'name']);
	}
}
