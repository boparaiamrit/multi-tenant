<?php

namespace Hyn\Tenancy\Models;


use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Laracasts\Presenter\PresentableTrait;

class BaseModel extends Model
{
	use PresentableTrait, SoftDeletes;
	
	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
		
		$this->setConnection(config('multitenant.database'));
	}
}
