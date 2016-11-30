<?php

namespace Boparaiamrit\Tenancy\Models;


use Jenssegers\Mongodb\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

/**
 * Boparaiamrit\Tenancy\Models
 *
 * @property string         $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @method static Model all($column = [])
 * @method static Model get($column = [])
 * @method static Model first($column = [])
 * @method static Model find($id, $column = [])
 * @method static Model findOrFail($id, $column = [])
 * @method static Model firstOrNew($attributes)
 * @method static Model firstOrCreate($attributes)
 * @method static Builder orderBy($column, $sort)
 * @method static Builder whereIn($column, array $values)
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder raw($mixed)
 * @method static Builder count()
 * @method static Builder paginate($perPage)
 */
class BaseModel extends Model
{
	use SoftDeletes;
	
	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
		
		$this->setConnection(config('multitenant.database'));
	}
}