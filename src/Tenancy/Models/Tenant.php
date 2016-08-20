<?php

namespace Hyn\Tenancy\Models;

/**
 * @deprecated Use Customer
 *
 * @info       the term tenant was quite confusing.
 */
class Tenant extends BaseModel
{
	protected $table = 'customers';
}
