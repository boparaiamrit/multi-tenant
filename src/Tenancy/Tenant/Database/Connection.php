<?php

namespace Hyn\Tenancy\Tenant\Database;


use Config;
use DB;
use Hyn\Framework\Exceptions\TenantDatabaseException;
use Hyn\Tenancy\Models\Website;

/**
 * Class Connection
 *
 * @package Hyn\Tenancy\Tenant\Database
 */
abstract class Connection
{
	/**
	 * See the multitenant configuration file. Configuration set
	 * to use separate databases.
	 */
	const TENANT_MODE_SEPARATE_DATABASE = 'database';
	
	/**
	 * See the multitenant configuration file. Configuration set
	 * to use prefixed table in same database.
	 */
	const TENANT_MODE_TABLE_PREFIX = 'prefix';
	/**
	 * Current active global tenant connection.
	 *
	 * @var string
	 */
	protected static $current;
	/**
	 * @var string
	 */
	public $name;
	/**
	 * @var Website
	 */
	protected $Website;
	/**
	 * @var \Illuminate\Database\Connection
	 */
	protected $connection;
	
	public function __construct(Website $Website)
	{
		$this->Website = $Website;
		
		$this->name = $this->Website->identifier;
		
		$this->setup();
	}
	
	/**
	 * Sets the tenant database connection.
	 */
	public function setup()
	{
		Config::set("database.connections.{$this->name}", $this->config());
	}
	
	/**
	 * Generic configuration for tenant.
	 *
	 * @return array
	 * @throws TenantDatabaseException
	 * @throws \Laracasts\Presenter\Exceptions\PresenterException
	 */
	abstract protected function config();
	
	public static function systemConnectionName()
	{
		return 'mongodb';
	}
	
	/**
	 * Checks whether current connection is set as global tenant connection.
	 *
	 * @return bool
	 */
	public function isCurrent()
	{
		return $this->name === static::getCurrent();
	}
	
	/**
	 * Loads the currently set global tenant connection name.
	 *
	 * @return string
	 */
	public static function getCurrent()
	{
		return static::$current;
	}
	
	/**
	 * Sets current global tenant connection.
	 */
	public function setCurrent()
	{
		static::$current = $this->name;
		
		Config::set(sprintf('database.connections.%s', static::tenantConnectionName()), $this->config());
	}
	
	/**
	 * Central getter for tenant connection name.
	 *
	 * @return string
	 */
	public static function tenantConnectionName()
	{
		return config('multitenant.db.tenant-connection-name', 'tenant');
	}
	
	/**
	 * Loads connection for this database.
	 *
	 * @return \Illuminate\Database\Connection
	 */
	public function get()
	{
		if (is_null($this->connection)) {
			$this->setup();
			$this->connection = DB::connection($this->name);
		}
		
		return $this->connection;
	}
	
	/**
	 * @return bool
	 */
	abstract public function create();
	
	
	/**
	 * @throws \Exception
	 *
	 * @return bool
	 */
	abstract public function delete();
	
}
