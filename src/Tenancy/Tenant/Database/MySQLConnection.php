<?php

namespace Hyn\Tenancy\Tenant\Database;


use DB;
use Hyn\Framework\Exceptions\TenantDatabaseException;
use Hyn\Tenancy\Models\Website;

/**
 * Class MySQLConnection
 *
 * @package Hyn\Tenancy\Tenant\Database
 */
class MySQLConnection extends Connection
{
	public function __construct(Website $Website)
	{
		parent::__construct($Website);
	}
	
	protected function config()
	{
		/** @var array $config */
		$config = config(sprintf('database.connections.%s', self::systemConnectionName()));
		
		if (config('multitenant.db.tenant-division-mode') == static::TENANT_MODE_SEPARATE_DATABASE) {
			$config['database'] = $this->Website->identifier;
		} elseif (config('multitenant.db.tenant-division-mode') == static::TENANT_MODE_TABLE_PREFIX) {
			$config['prefix'] = $this->Website->identifier;
		} else {
			throw new TenantDatabaseException('Unknown database division mode configured in the multitenant configuration file.');
		}
		
		return $config;
	}
	
	/**
	 * Central getter for system connection name.
	 *
	 * @return string
	 */
	public static function systemConnectionName()
	{
		return config('multitenant.db.system-connection-name', 'mysql');
	}
	
	/**
	 * @return bool
	 */
	public function create()
	{
		if (config('multitenant.db.tenant-division-mode') != static::TENANT_MODE_SEPARATE_DATABASE) {
			return false;
		}
		
		$config = $this->config();
		
		return DB::connection(static::systemConnectionName())->transaction(function () use ($config) {
			if (!DB::connection(static::systemConnectionName())->statement("create database if not exists `{$config['database']}`")) {
				throw new TenantDatabaseException("Could not create database {$config['database']}");
			}
			if (!DB::connection(static::systemConnectionName())->statement("grant all on `{$config['database']}`.* to `{$config['username']}`@'{$config['host']}' identified by '{$config['password']}'")) {
				throw new TenantDatabaseException("Could not create or grant privileges to user {$config['username']} for {$config['database']}");
			}
			
			return true;
		});
	}
	
	/**
	 * @throws \Exception
	 *
	 * @return bool
	 */
	public function delete()
	{
		if (config('multitenant.db.tenant-division-mode') != static::TENANT_MODE_SEPARATE_DATABASE) {
			return false;
		}
		
		$config = $this->config();
		
		return DB::connection(static::systemConnectionName())->transaction(function () use ($config) {
			if (!DB::connection(static::systemConnectionName())->statement("drop database `{$config['database']}`")) {
				throw new TenantDatabaseException("Could not drop database {$config['database']}");
			}
			
			return true;
		});
	}
}
