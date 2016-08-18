<?php

namespace Hyn\Webserver\Observers;

use Hyn\Webserver\Commands\WebserverCommand;
use Illuminate\Foundation\Bus\DispatchesJobs;

class HostnameObserver
{
    use DispatchesJobs;

    public function created($model)
    {
        $this->dispatch(
            new WebserverCommand($model->website_id, 'create')
        );
    }

    public function updated($model)
    {
        $this->dispatch(
            new WebserverCommand($model->website_id, 'update')
        );
    }

    public function deleting($model)
    {
        $this->dispatch(
            new WebserverCommand($model->website_id, 'delete')
        );
    }
}
