<?php

namespace Hyn\Tenancy\Abstracts\Models\MongoDB;


use Hyn\Framework\Models\MongoDB\AbstractModel;
use Hyn\Tenancy\Tenant\Database\MongoDBConnection;

class TenantModel extends AbstractModel
{
	public function getConnectionName()
	{
		return MongoDBConnection::tenantConnectionName();
	}
}
