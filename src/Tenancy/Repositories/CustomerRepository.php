<?php

namespace Boparaiamrit\Tenancy\Repositories;

use Boparaiamrit\Framework\Repositories\BaseRepository;
use Boparaiamrit\Tenancy\Contracts\CustomerRepositoryContract;
use Boparaiamrit\Tenancy\Models\Customer;

class CustomerRepository extends BaseRepository implements CustomerRepositoryContract
{
    /**
     * @var Customer
     */
    protected $customer;

    /**
     * Find a customer by name.
     *
     * @param $name
     *
     * @return Customer
     */
    public function findByName($name)
    {
        return $this->customer->where('name', $name)->first();
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
        $customer = $this->customer->where('name', $name)->first();

        return $customer ? $customer->delete() : null;
    }
}
