<?php

namespace Boparaiamrit\Tenancy\Contracts;


use Boparaiamrit\Framework\Contracts\BaseRepositoryContract;
use Boparaiamrit\Tenancy\Models\Customer;

/**
 * Interface CustomerRepositoryContract
 *
 * @package Boparaiamrit\Tenancy\Contracts
 *
 * @property Customer $Model
 */
interface CustomerRepositoryContract extends BaseRepositoryContract
{
	/**
	 * Load all customers.
	 *
	 * @return mixed
	 */
	public function all();
	
	/**
	 * Removes customer and everything related.
	 *
	 * @param $name
	 *
	 * @return bool|null
	 */
	public function forceDeleteByName($name);
	
	/**
	 * Find a customer by name.
	 *
	 * @param $name
	 *
	 * @return Customer
	 */
	public function findByName($name);
}
