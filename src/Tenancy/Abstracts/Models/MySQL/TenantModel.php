<?php

namespace Hyn\Tenancy\Abstracts\Models\MySQL;

use Hyn\Framework\Models\MySQL\AbstractModel;
use Hyn\Tenancy\Tenant\DatabaseConnection;

class TenantModel extends AbstractModel
{
    public function getConnectionName()
    {
        return DatabaseConnection::tenantConnectionName();
    }
}
