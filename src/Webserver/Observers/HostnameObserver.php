<?php

namespace Hyn\Webserver\Observers;

use Hyn\Webserver\Commands\WebserverCommand;
use Illuminate\Foundation\Bus\DispatchesJobs;

class HostnameObserver
{
    use DispatchesJobs;

    public function created($Model)
    {
        $this->dispatch(
            new WebserverCommand($Model->website_id, 'create')
        );
    }

    public function updated($Model)
    {
        $this->dispatch(
            new WebserverCommand($Model->website_id, 'update')
        );
    }

    public function deleting($Model)
    {
        $this->dispatch(
            new WebserverCommand($Model->website_id, 'delete')
        );
    }
}
