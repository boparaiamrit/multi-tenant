<?php

namespace Boparaiamrit\Tenancy\Contracts;

use Boparaiamrit\Framework\Contracts\BaseRepositoryContract;
use Boparaiamrit\Tenancy\Models\Host;
use Boparaiamrit\Tenancy\Models\Certificate;

interface CertificateRepositoryContract extends BaseRepositoryContract
{
    /**
     * @param Host $Host
     *
     * @return Certificate
     */
    public function findByHost(Host $Host);
}
