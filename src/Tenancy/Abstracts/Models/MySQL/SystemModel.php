<?php

namespace Hyn\Tenancy\Abstracts\Models\MySQL;

use Hyn\Framework\Models\MySQL\AbstractModel;
use Hyn\Tenancy\Tenant\DatabaseConnection;

class SystemModel extends AbstractModel
{
    public function getConnectionName()
    {
        return DatabaseConnection::systemConnectionName();
    }
}
