<?php

namespace Boparaiamrit\Tenancy\Repositories;

use Boparaiamrit\Framework\Repositories\BaseRepository;
use Boparaiamrit\Tenancy\Contracts\CustomerRepositoryContract;
use Boparaiamrit\Tenancy\Models\Customer;

class CustomerRepository extends BaseRepository implements CustomerRepositoryContract
{
    /**
     * @var Customer|CustomerRepositoryContract
     */
    protected $Model;

    /**
     * Find a customer by name.
     *
     * @param $name
     *
     * @return Customer
     */
    public function findByName($name)
    {
        return $this->Model->where('name', $name)->first();
    }

    /**
     * Removes customer and everything related.
     *
     * @param $name
     *
     * @return bool|null
     */
    public function forceDeleteByName($name)
    {
		/** @var Customer $Customer */
		$Customer = $this->Model->where('name', $name)->first();

        $Customer ? $Customer->delete() : null;
		
		return true;
    }
}
