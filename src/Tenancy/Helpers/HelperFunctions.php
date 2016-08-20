<?php

use Hyn\Tenancy\Contracts\CustomerRepositoryContract;
use Hyn\Tenancy\Contracts\DirectoryContract;
use Hyn\Tenancy\Contracts\HostnameRepositoryContract;
use Hyn\Tenancy\Contracts\WebsiteRepositoryContract;
use Hyn\Tenancy\Models\Customer;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Models\Website;

if (!function_exists('website')) {
	/**
	 * Loads a tenant website, or the current one.
	 *
	 * @param null $id
	 *
	 * @return Website|bool
	 */
	function website($id = null)
	{
		if (!empty($id)) {
			return app(WebsiteRepositoryContract::class)->findById($id);
		}
		
		$hostname = app('tenant.hostname');
		
		return $hostname ? $hostname->website : false;
	}
}

if (!function_exists('customer')) {
	/**
	 * Loads a customer, or the current one.
	 *
	 * @param null $id
	 *
	 * @return Customer|bool
	 */
	function customer($id = null)
	{
		if (!empty($id)) {
			return app(CustomerRepositoryContract::class)->findById($id);
		}
		
		$hostname = app('tenant.hostname');
		
		return $hostname ? $hostname->customer : false;
	}
}

if (!function_exists('hostname')) {
	/**
	 * Loads a hostname, or the current one.
	 *
	 * @param null $id
	 *
	 * @return Hostname|bool
	 */
	function hostname($id = null)
	{
		if (!empty($id)) {
			return app(HostnameRepositoryContract::class)->findById($id);
		}
		
		$hostname = app('tenant.hostname');
		
		return $hostname ? $hostname : false;
	}
}
