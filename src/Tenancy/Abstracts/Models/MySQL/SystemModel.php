<?php

namespace Hyn\Tenancy\Abstracts\Models\MySQL;

use Hyn\Framework\Models\MySQL\AbstractModel;
use Hyn\Tenancy\Tenant\Database\MySQLConnection;

class SystemModel extends AbstractModel
{
    public function getConnectionName()
    {
        return MySQLConnection::systemConnectionName();
    }
}
