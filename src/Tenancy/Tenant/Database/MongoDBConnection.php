<?php

namespace Hyn\Tenancy\Tenant\Database;


use Hyn\Tenancy\Models\Website;

/**
 * Class MySQLConnection
 *
 * @package Hyn\Tenancy\Tenant\Database
 */
class MongoDBConnection extends Connection
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
		return 'mongodb';
	}
	
	/**
	 * @return bool
	 */
	public function create()
	{
		// No need to create db in MongoDB
		return true;
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
		
		/** @var \MongoDB\Client $Client */
		$Client = app('db')->getMongoClient();
		$Client->dropDatabase($config['database']);
		
		return true;
	}
}
