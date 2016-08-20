<?php

namespace Hyn\Tenancy\Presenters;

use Hyn\Framework\Presenters\AbstractModelPresenter;

/**
 * @property mixed hostname
 */
class HostnamePresenter extends AbstractModelPresenter
{
    /**
     * @return string
     */
    public function icon()
    {
        return 'management-interface::icon.hostname';
    }

    /**
     * @return mixed
     */
    public function name()
    {
        return $this->hostname;
    }
}
