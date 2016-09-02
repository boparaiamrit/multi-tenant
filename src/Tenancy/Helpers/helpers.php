<?php

use Boparaiamrit\Tenancy\Contracts\CustomerRepositoryContract;
use Boparaiamrit\Tenancy\Contracts\HostRepositoryContract;


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
		
		$Host = app(HostRepositoryContract::class)->findByHostname(app('hostname'));
		
		return $Host ? $Host->customer : false;
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
		
		$Host = app(HostRepositoryContract::class)->findByHostname(app('hostname'));
		
		return $Host ? $Host : false;
	}
}

if (!function_exists('hostname_cleaner')) {
	function hostname_cleaner($hostname = null)
	{
		return trim(str_replace(['.'], '', $hostname));
	}
}
