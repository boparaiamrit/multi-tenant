<?php

use Boparaiamrit\Tenancy\Models\Customer;
use Boparaiamrit\Tenancy\Models\Host;
use Jenssegers\Mongodb\Eloquent\Model;

if (!function_exists('customer')) {
	/**
	 * Loads a customer, or the current one.
	 *
	 * @param null $id
	 *
	 * @return Model|Customer|bool
	 */
	function customer($id = null)
	{
		if (!empty($id)) {
			return Customer::find($id);
		}
		
		$Host = Host::where('hostname', app('hostname'))
					->first();
		
		return $Host ? $Host->customer : false;
	}
}

if (!function_exists('host')) {
	/**
	 * Loads a host, or the current one.
	 *
	 * @param null $id
	 *
	 * @return Model|Host|bool
	 */
	function host($id = null)
	{
		if (!empty($id)) {
			return Host::find($id);
		}
		
		/** @var Host $Host */
		$Host = Host::where('hostname', app('hostname'))
					->first();
		
		return $Host ? $Host : false;
	}
}

if (!function_exists('hostname_cleaner')) {
	function hostname_cleaner($hostname = null)
	{
		return trim(str_replace(['.'], '', $hostname));
	}
}
