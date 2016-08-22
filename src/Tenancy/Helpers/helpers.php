<?php

use Boparaiamrit\Tenancy\Contracts\CustomerRepositoryContract;
use Boparaiamrit\Tenancy\Contracts\HostRepositoryContract;
use Boparaiamrit\Tenancy\Helpers\RequestHelper;


if (!function_exists('customer')) {
	/**
	 * Loads a customer, or the current one.
	 *
	 * @param null $id
	 *
	 * @return CustomerRepositoryContract|bool
	 */
	function customer($id = null)
	{
		if (!empty($id)) {
			return app(CustomerRepositoryContract::class)->findById($id);
		}
		
		$host = app(RequestHelper::CUSTOMER_HOST);
		
		return $host ? $host->customer : false;
	}
}

if (!function_exists('host')) {
	/**
	 * Loads a host, or the current one.
	 *
	 * @param null $id
	 *
	 * @return HostRepositoryContract|bool
	 */
	function host($id = null)
	{
		if (!empty($id)) {
			return app(HostRepositoryContract::class)->findById($id);
		}
		
		$host = app(RequestHelper::CUSTOMER_HOST);
		
		return $host ? $host : false;
	}
}
