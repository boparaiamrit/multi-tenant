<?php

namespace Boparaiamrit\Tenancy\Presenters;

use Boparaiamrit\Framework\Presenters\AbstractModelPresenter;

class CustomerPresenter extends AbstractModelPresenter
{
    /**
     * @return mixed
     */
    public function name()
    {
        return $this->entity->name;
    }
}
