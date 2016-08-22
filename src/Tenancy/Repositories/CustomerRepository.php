<?php

namespace Boparaiamrit\Tenancy\Repositories;

use Boparaiamrit\Framework\Repositories\BaseRepository;
use Boparaiamrit\Tenancy\Contracts\CustomerRepositoryContract;
use Boparaiamrit\Tenancy\Models\Customer;

class CustomerRepository extends BaseRepository implements CustomerRepositoryContract
{
    /**
     * Find a customer by name.
     *
     * @param $name
     *
     * @return Customer|mixed
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
