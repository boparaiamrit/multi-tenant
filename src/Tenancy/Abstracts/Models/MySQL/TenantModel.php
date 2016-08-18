<?php

namespace Hyn\Tenancy\Abstracts\Models\MySQL;

use Hyn\Framework\Models\MySQL\AbstractModel;
use Hyn\Tenancy\Tenant\Database\MySQLConnection;

class TenantModel extends AbstractModel
{
    public function getConnectionName()
    {
        return MySQLConnection::tenantConnectionName();
    }
}
